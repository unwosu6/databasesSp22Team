-- DROP PROCEDURE IF EXISTS GetCountryName;

-- DELIMITER //

-- CREATE PROCEDURE GetCountryName(IN code VARCHAR(3))

-- BEGIN
--     IF EXISTS(SELECT * FROM Country AS C WHERE C.countryCode = code) THEN
--         SET @sql = CONCAT('SELECT C.countryName ',
--                           'FROM Country AS C ',
--                           'WHERE C.countryCode = ?');
--         PREPARE stmt FROM @sql;
--         EXECUTE stmt USING code;
--         DEALLOCATE PREPARE stmt;
--     END IF;
-- END;//

-- DELIMITER ;

DROP PROCEDURE IF EXISTS GetCountries;

DELIMITER //

CREATE PROCEDURE GetCountries()

BEGIN
    IF EXISTS(SELECT * FROM Country) THEN
        SET @sql = CONCAT('SELECT * ',
                          'FROM Country');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END;//

DELIMITER ;