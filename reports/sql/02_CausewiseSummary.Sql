--Stats2--2024 Causewise Summary

-- SQL to load Total Appeals in particular Year:
-- Just need to pass Year parameter in where condition - 'where Year=2024'

select "### 1. Total Appeals matching with the Transactions" as "DEBUG_MSG" from dual;

select Year(a.DateOfTx) as Year, count(distinct appealId) as "Appeal Count" from TblTxDetails a LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id where Year=2024 and a.appealId<>-1 group by Year;

-- SQL to load Causewise Appeals in particular Year:
-- Just need to pass Year parameter in where condition - 'where Year=2024' and change Null to 'General'

select "### 2. Total Appeals Cause wise matching with the Transactions" as "DEBUG_MSG" from dual;

select Year(a.DateOfTx) as Year, (case when b.cause IS NULL then 'General' else b.cause end) as cause, count(distinct appealId) as "Total Appeals" from TblTxDetails a LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id where Year=2024 and a.appealId<>-1 group by Year, b.cause;

-- SQL to load Cause and Statuswise Appeals in particular Year:
-- Just need to pass Year parameter in where condition - 'where Year=2024' and change Null to 'General'

select "### 3. Total Appeals Cause wise and Status wise matching with the Transactions" as "DEBUG_MSG" from dual;

select Year(a.DateOfTx) as Year, (case when b.status IS NULL then 'In-Progress' else b.status end) as status, (case when b.cause IS NULL then 'General' else b.cause end) as cause, count(distinct appealId) as "Total Appeals" from TblTxDetails a LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id where Year=2024 and a.appealId<>-1 group by Year, b.status, b.cause ;

-- SQL to load Appeals that are present in the System but has not had any Transactions
-- Just need to pass Year parameter in where condition -- 'where Year(DateEntered)=2024' and 'where Year(DateOfTx)=2024'

select "### 4. Appeals exists in the System without any Transactions" as "DEBUG_MSG" from dual;

select Year(DateEntered) As Year, Status, Cause, Count(*) from TblAppealInfo where Year(DateEntered)=2024 and Id NOT in (select AppealId from TblTxDetails where Year(DateOfTx)=2024)group by Status, Cause;

-- SQL to load Appeals that are related to the COVID Pandemic
-- Just need to pass Year parameter in where condition -- 'where Year(DateEntered)=2024'

select "### 5. New Appeals pertaining to COVID Pandemic" as "DEBUG_MSG" from dual;

select Year(DateEntered) as Year, Id, Name, Cause, Status from TblAppealInfo where Year(DateEntered)=2024 and UPPER(Name) like '%COVID%' order by Id asc;


select "### 6. Continuing Appeals pertaining to COVID Pandemic" as "DEBUG_MSG" from dual;

-- select Year(DateEntered) as Year, Id, Name, Cause, Status from TblAppealInfo where Year(DateEntered)>=2021 and UPPER(Name) like '%COVID%' order by Id asc;

-- select distinct Year(a.DateOfTx) AS Year, a.AppealId, b.Name from TblTxDetails a, TblAppealInfo b where a.AppealId=b.Id and a.DateOfTx between '2024-01-01' and '2024-12-31' and UPPER(b.Name) like '%COVID%' order by a.AppealId asc;

select distinct Year(a.DateOfTx) as Year, Year(b.DateEntered) as Year_Created, a.AppealId, b.Name from TblTxDetails a, TblAppealInfo b where a.AppealId=b.Id and a.DateOfTx between '2024-01-01' and '2024-12-31' and UPPER(b.Name) like '%COVID%' order by a.AppealId asc;
