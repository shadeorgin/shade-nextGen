-- Stats1--2024 Appeal Summary

-- SQL to load Total Appeals in particular Year:
-- Just need to pass Year parameter in where condition - 'where Year=2024'

select "### 1. Total Appeals matching with the Transactions" as "DEBUG_MSG" from dual;

select Year(a.DateOfTx) as Year, count(distinct appealId) as "Appeal Count" from tbltxdetails a LEFT OUTER JOIN tblAppealInfo b ON a.AppealId=b.Id where Year=2024 and a.appealId<>-1 group by Year;

-- SQL to load Statuswise Appeals in particular Year:
-- Just need to pass Year parameter in where condition - 'where Year=2024'

-- It generally returns NULL for all the transactions mapped against normal SHaDE
-- Contributions as AppealId 0, but we do NOT have any appeal with the Id 0 in TblAppealInfo.
-- Hence, we get that listed as In-Progress (and we see two different entries with the status In-Progress) and at the end we sum them up to have only one entry.
select "### 2. Statuswise Appeals matching with the Transactions" as "DEBUG_MSG" from dual;

select Year(a.DateOfTx) as Year, (case when b.status IS NULL then 'In-Progress' else b.status end) as status, count(distinct appealId) as "Total Appeals" from TblTxDetails a LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id where Year=2024 and a.appealId<>-1 group by Year, b.status;

-- SQL to load Appeals that are present in the System but has not had any Transactions
-- Just need to pass Year parameter in where condition -- 'where Year(DateEntered)=2024' and 'where Year(DateOfTx)=2024'

select "### 3. Appeal Present in the System without any Transactions" as "DEBUG_MSG" from dual;

select Year(DateEntered) As Year, Status, Count(*) from TblAppealInfo where Year(DateEntered)=2024 and Id NOT in (select AppealId from TblTxDetails where Year(DateOfTx)=2024)group by Status;
