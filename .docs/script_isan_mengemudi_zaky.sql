-- Query berdasarkan kebutuhan sistem

-- 5 Admin Memonitor aktivitas platform 
    -- A. Function Total Pendapatan Vendor

DELIMITER //

CREATE FUNCTION total_pendapatan_vendor(vendorId BIGINT)
RETURNS DECIMAL(15,2)
DETERMINISTIC
BEGIN
    DECLARE totalPendapatan DECIMAL(15,2);

    SELECT SUM(c.total_price)
    INTO totalPendapatan
    FROM transactions t
    JOIN carts c ON t.cart_id = c.id
    WHERE t.car_rental_id = vendorId
      AND t.status = 'SUCCEED';

    RETURN IFNULL(totalPendapatan, 0);
END //

DELIMITER ;

    -- cara menggunakan:
SELECT total_pendapatan_vendor(16) AS TotalPendapatan;

    -- B Ranking Vendor Berdasarkan Pendapatan
SELECT 
    cr.id,
    cr.car_rental_name,
    AVG(r.rating) AS avg_rating,
    RANK() OVER (ORDER BY AVG(r.rating) DESC) AS ranking_vendor
FROM car_rentals cr
LEFT JOIN reviews r ON cr.id = r.car_rental_id
GROUP BY cr.id;

    -- C. TRANSACTION total pendapatan dari semua vendor
    -- Transaction ini digunakan agar laporan pendapatan dibaca dari snapshot data yang sama dan tidak terpengaruh oleh perubahan dari session lain.

START TRANSACTION;

SELECT SUM(c.total_price) AS total_pendapatan
FROM transactions t
JOIN carts c ON t.cart_id = c.id
WHERE t.status = 'SUCCEED';

COMMIT;

    -- Input data untuk testing transaction total pendapatan semua vendor
    -- INSERT INTO carts (id, user_id, car_id, quantity, total_price)
    -- VALUES (200, 5, 1, 1, 300000);

    -- INSERT INTO transactions
    -- (car_rental_id, user_id, cart_id, title, payment_method, status)
    -- VALUES (1, 5, 200, 'Rental Mobil', 'TRANSFER', 'SUCCEED');

    -- D. Procedur Laporan Pendapatan Vendor

DELIMITER //

CREATE PROCEDURE laporan_pendapatan_vendor()
BEGIN
    SELECT 
        cr.id AS vendor_id,
        cr.car_rental_name,
        COUNT(t.id) AS total_transaksi,
        IFNULL(SUM(c.total_price), 0) AS total_pendapatan
    FROM car_rentals cr
    LEFT JOIN transactions t 
        ON cr.id = t.car_rental_id AND t.status = 'SUCCEED'
    LEFT JOIN carts c 
        ON t.cart_id = c.id
    GROUP BY cr.id;
END //

DELIMITER ;
    -- cara menggunakan:
CALL laporan_pendapatan_vendor();


-- 6 memastikan ketersediaan mobil diperbarui secara otomatis
    -- A. Trigger Update Ketersediaan
DELIMITER //

CREATE TRIGGER trg_after_cart_insert
AFTER INSERT ON carts
FOR EACH ROW
BEGIN
    UPDATE cars
    SET stock = stock - NEW.quantity
    WHERE id = NEW.car_id;
END //

DELIMITER ;
    -- cara menggunakan:
    -- insert
INSERT INTO carts (user_id, car_id, quantity, total_price)
VALUES (1, 3, 1, 500000);
    -- cek ketersediaan mobil setelah insert
    SELECT car_name, stock
FROM cars
WHERE id = 3;

    -- B. Trigger Ubah Status Ke ON LOAN

DELIMITER //

CREATE TRIGGER trg_on_loan_by_time
BEFORE UPDATE ON loanings
FOR EACH ROW
BEGIN
    IF NEW.loan_date = CURDATE()
       AND NEW.loan_time <= CURTIME()
       AND OLD.status = 'APPROVED' THEN
        SET NEW.status = 'ON LOAN';
    END IF;
END //

DELIMITER ;

    -- cara menggunakan:
    -- data testing
    INSERT INTO loanings
(car_id, user_id, tenant_ktp, loan_date, loan_time,
 return_date_plan, return_time_plan, status, car_condition)
VALUES
(3, 1, 'ktp.jpg', CURDATE(), '08:00:00',
 CURDATE(), '18:00:00', 'APPROVED', 'BAIK');

    -- cek status sebelum update dan setelah update
    SELECT id, status
FROM loanings
WHERE car_id = 3;

    -- C. Transaction pada proses booking mobil

START TRANSACTION;

INSERT INTO carts (user_id, car_id, quantity, total_price)
VALUES (1, 3, 1, 500000);

INSERT INTO transactions
(car_rental_id, user_id, cart_id, title, payment_method, status)
VALUES (2, 1, LAST_INSERT_ID(), 'Rental Mobil', 'TRANSFER', 'WAITING FOR PAYMENT');

COMMIT;

    -- D. Procedure booking mobil
DELIMITER //

CREATE PROCEDURE book_car(
    IN p_user_id BIGINT,
    IN p_car_id BIGINT,
    IN p_quantity INT,
    IN p_total_price DECIMAL(8,2)
)
BEGIN
    START TRANSACTION;

    INSERT INTO carts (user_id, car_id, quantity, total_price)
    VALUES (p_user_id, p_car_id, p_quantity, p_total_price);

    INSERT INTO transactions
    (car_rental_id, user_id, cart_id, title, payment_method, status)
    VALUES
    (
        (SELECT car_rental_id FROM cars WHERE id = p_car_id),
        p_user_id,
        LAST_INSERT_ID(),
        'Rental Mobil',
        'TRANSFER',
        'WAITING FOR PAYMENT'
    );

    COMMIT;
END //

DELIMITER ;
    -- cara menggunakan:
CALL book_car(1, 3, 1, 500000);

    -- E. Function Cek Ketersediaan Mobil
DELIMITER //

CREATE FUNCTION is_car_available(carId BIGINT)
RETURNS BOOLEAN
DETERMINISTIC
BEGIN
    DECLARE available BOOLEAN;

    SELECT stock > 0
    INTO available
    FROM cars
    WHERE id = carId;

    RETURN IFNULL(available, FALSE);
END //

DELIMITER ;


    -- cara menggunakan:
SELECT is_car_available(3) AS KetersediaanMobil;

-- 7. Sistem harus menyimpan riwayat pengembalian mobil, termasuk kondisi kendaraan setelah digunakan.
    -- A. Procedure Simpan Riwayat Pengembalian Mobil
DELIMITER //

CREATE PROCEDURE return_car(
    IN p_loaning_id BIGINT,
    IN p_condition ENUM('SANGAT BAIK','BAIK','KURANG BAIK'),
    IN p_proof TEXT
)
BEGIN
    START TRANSACTION;

    INSERT INTO returnings
    (loaning_id, return_date, return_time, proof_of_return, car_condition)
    VALUES
    (p_loaning_id, CURDATE(), CURTIME(), p_proof, p_condition);

    UPDATE loanings
    SET status = 'DONE'
    WHERE id = p_loaning_id;

    COMMIT;
END //

DELIMITER ;

    -- cara menggunakan:
CALL return_car(1, 'BAIK', 'bukti_pengembalian.jpg');

    -- B. trigger Update Status Mobil Setelah Pengembalian

DELIMITER //

CREATE TRIGGER trg_after_return
AFTER INSERT ON returnings
FOR EACH ROW
BEGIN
    UPDATE cars c
    JOIN loanings l ON l.car_id = c.id
    SET c.stock = c.stock + 1
    WHERE l.id = NEW.loaning_id;
END //

DELIMITER ;

    -- C. Function Cek Riwayat Pengembalian Mobil (Kurang BAIK)
DELIMITER //

CREATE FUNCTION count_bad_condition(carId BIGINT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE total INT;

    SELECT COUNT(*)
    INTO total
    FROM returnings r
    JOIN loanings l ON r.loaning_id = l.id
    WHERE l.car_id = carId
      AND r.car_condition = 'KURANG BAIK';

    RETURN total;
END //

DELIMITER ;

    -- cara menggunakan:

SELECT count_bad_condition(3);

-- 8. Sistem harus dapat menyimpan rating dan ulasan pelanggan
    -- A. Procedure Simpan Rating dan Ulasan

DELIMITER //

CREATE PROCEDURE add_review(
    IN p_user_id BIGINT,
    IN p_car_rental_id BIGINT,
    IN p_rating INT,
    IN p_comment TEXT
)
BEGIN
    INSERT INTO reviews (user_id, car_rental_id, rating, comment)
    VALUES (p_user_id, p_car_rental_id, p_rating, p_comment);
END //

DELIMITER ;

    -- cara menggunakan:
CALL add_review(1, 2, 5, 'Pelayanan sangat baik dan mobilnya bersih.');

    -- B. Trigger Update Rata-rata Rating Vendor
    
DELIMITER //

CREATE TRIGGER trg_after_review_insert
AFTER INSERT ON reviews
FOR EACH ROW
BEGIN
    UPDATE car_rentals
    SET average_rating = (
        SELECT AVG(rating)
        FROM reviews
        WHERE car_rental_id = NEW.car_rental_id
    )
    WHERE id = NEW.car_rental_id;
END //

DELIMITER ;
    -- cara menggunakan:

    -- C. Function Get Rata-rata Rating Vendor

DELIMITER //

CREATE FUNCTION get_average_rating(vendorId BIGINT)
RETURNS DECIMAL(8,2)
DETERMINISTIC
BEGIN
    DECLARE avgRating DECIMAL(8,2);

    SELECT AVG(rating)
    INTO avgRating
    FROM reviews
    WHERE car_rental_id = vendorId;

    RETURN IFNULL(avgRating, 0);
END //

DELIMITER ;

    -- cara menggunakan:
SELECT get_average_rating(2) AS RataRataRating;

    -- D. Ranking Vendor Berdasarkan Rating

SELECT
    cr.car_rental_name,
    cr.average_rating,
    RANK() OVER (ORDER BY cr.average_rating DESC) AS ranking_vendor
FROM car_rentals cr;







