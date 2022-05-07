-- QuestionTwo.sql
-- Reports list of bids on item with specified number

DELIMITER //

DROP PROCEDURE IF EXISTS QuestionTwo //

CREATE PROCEDURE QuestionTwo(IN item VARCHAR(4))
BEGIN
   IF EXISTS(SELECT * FROM WorksIn WHERE year = item) THEN
      SELECT W.countryCode, W.sectorID, W.sex, W.monthlyEarnings
        FROM WorksIn AS W, WorksIn AS Wmax
        WHERE W.countryCode = Wmax.countryCode 
        AND W.year = Wmax.year AND W.year = 2016 
        AND Wmax.sectorID != 'Total' 
        GROUP BY W.countryCode, W.monthlyEarnings, W.sectorID, W.sex
        HAVING W.monthlyEarnings = max(Wmax.monthlyEarnings)
        ORDER BY W.monthlyEarnings DESC;
   END IF;
END; //

DELIMITER ;
