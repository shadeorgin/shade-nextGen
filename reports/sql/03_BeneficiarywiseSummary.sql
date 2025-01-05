-- ==================================================
-- Stats3--2024 Appeals Beneficiary Summary
-- ==================================================
-- 2024 Appeal Beneficiary Summary
-- The following status show the beneficiary details and the Cause of the appeals we had taken in the year 2024.
-- Just need to pass Year parameter in where condition - 'where Year(a.DateOfTx)=2024'

/*select "### 1. Statuswise Beneficiaries matching with the Transactions" as "DEBUG_MSG" from dual;

select Year(a.DateEntered) as Year, (case when a.status IS NULL then 'In-Progress' else a.status end) as status, count(distinct beneficiaryId) as "Total Beneficiaries" from TblAppealInfo a LEFT OUTER JOIN TblBeneficiary b ON a.BeneficiaryId=b.Id where Year(a.DateEntered)=2024 and a.Id<>-1 group by Year, a.status;

+------+-------------+---------------------+
| Year | status      | Total Beneficiaries |
+------+-------------+---------------------+
| 2024 | Cancelled   |                   3 |
| 2024 | Completed   |                  11 |
| 2024 | In-Progress |                   5 |
| 2024 | New         |                   3 |
+------+-------------+---------------------+

select Year, count(distinct Id) as "Beneficiary Count" from TblTxDetails a LEFT OUTER JOIN TblBeneficiary b ON a.AppealId=b.Id where Year=2024 and a.appealId<>-1 group by Year;
*/

select "### 2. Total Beneficiaries matching with the Transactions" as "DEBUG_MSG" from dual;

select distinct substr(a.appealId,1,5) as Id, b.Name as AppealName, substr(c.Name, 1, 30) as Beneficiary, (case when b.status IS NULL then 'In-Progress' else b.status end) as status, (case when b.cause IS NULL then 'General' else b.cause end) as cause, c.Category, c.Type from TblTxDetails a LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id INNER JOIN TblBeneficiary c ON b.BeneficiaryId=c.Id and Year(a.DateOfTx)=2024 and a.appealId<>-1 order by a.appealId;
