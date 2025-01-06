-- ==================================================
-- Stats4--2024 Appeals - Transaction Summary
-- ==================================================
-- 2024 Appeal Transaction Summary
-- The following status show the status wise count of the appeals we have worked on the year 2024, including the historical but continuing.
-- Just need to pass Year parameter in where condition - 'where Year=2024'

-- Total Appeals
select "### 1. Summary of all Transactions" as "DEBUG_MSG" from dual;
select Year(DateOfTx) as Year, TxType, sum(Amount) from TblTxDetails where Year=2024 and AppealId >= 0 group by Year, TxType;

select "### 2. Summary of all Transactions - excluding the Internal Funds" as "DEBUG_MSG" from dual;

select Year(DateOfTx) as Year, TxType, sum(Amount) from TblTxDetails where Year=2024 and AppealId >= 0 AND Remarks NOT LIKE '%SHaDE%Funds%' group by Year, TxType;

-- Appealwise Transaction Summary
select "### 3. Appeal wise Transaction Summary" as "DEBUG_MSG" from dual;

select
    Year(DateOfTx) as Year,
    AppealId,
    CASE 
        WHEN a.AppealId = 0 THEN 'Generic SHaDE Appeal'
        ELSE COALESCE(b.Name, 'Unknown Appeal')
    END as AppealName,
    CASE 
        WHEN a.AppealId = 0 THEN 'Active'
        ELSE COALESCE(b.Status, 'Unknown')
    END as AppealStatus,
    -- sum(Amount) as TotalAmount,
    sum(CASE WHEN TxType='C' then a.Amount else 0 end) as CREDIT,
    sum(CASE WHEN TxType='D' then a.Amount else 0 end) as DEBIT,
    sum(CASE WHEN TxType='C' then a.Amount else 0 end)-sum(CASE WHEN TxType='D' then a.Amount else 0 end) as EffectiveTotal
from
    TblTxDetails a
    -- INNER JOIN
    LEFT OUTER JOIN
    TblAppealInfo b
    ON a.AppealId=b.ID
where
    Year=2024
    and AppealId >= 0
group by
    Year, AppealId;

select "### 4. Appeal wise Transaction Summary - Excluding Internal Funds" as "DEBUG_MSG" from dual;

select
    Year(DateOfTx) as Year,
    AppealId,
    CASE 
        WHEN a.AppealId = 0 THEN 'Generic SHaDE Appeal'
        ELSE COALESCE(b.Name, 'Unknown Appeal')
    END as AppealName,
    CASE 
        WHEN a.AppealId = 0 THEN 'Active'
        ELSE COALESCE(b.Status, 'Unknown')
    END as AppealStatus,
    -- sum(Amount) as TotalAmount,
    sum(CASE WHEN TxType='C' then a.Amount else 0 end) as CREDIT,
    sum(CASE WHEN TxType='D' then a.Amount else 0 end) as DEBIT,
    sum(CASE WHEN TxType='C' then a.Amount else 0 end)-sum(CASE WHEN TxType='D' then a.Amount else 0 end) as EffectiveTotal
from
    TblTxDetails a
    -- INNER JOIN
    LEFT OUTER JOIN
    TblAppealInfo b
    ON a.AppealId=b.ID
where
    Year=2024
    and AppealId >= 0
    and a.Remarks NOT LIKE '%SHaDE%Funds%'
group by
    Year, AppealId;

-- COVID Specific Transaction Summary
select "### 5. Appeal wise Transaction Summary - Excluding Internal Funds - COVID Specific" as "DEBUG_MSG" from dual;

select
    Year(DateOfTx) as Year,
    AppealId,
    CASE 
        WHEN a.AppealId = 0 THEN 'Generic SHaDE Appeal'
        ELSE COALESCE(b.Name, 'Unknown Appeal')
    END as AppealName,
    CASE 
        WHEN a.AppealId = 0 THEN 'Active'
        ELSE COALESCE(b.Status, 'Unknown')
    END as AppealStatus,
    -- sum(Amount) as TotalAmount,
    sum(CASE WHEN TxType='C' then a.Amount else 0 end) as CREDIT,
    sum(CASE WHEN TxType='D' then a.Amount else 0 end) as DEBIT,
    sum(CASE WHEN TxType='C' then a.Amount else 0 end)-sum(CASE WHEN TxType='D' then a.Amount else 0 end) as EffectiveTotal
from
    TblTxDetails a
    -- INNER JOIN
    LEFT OUTER JOIN
    TblAppealInfo b
    ON a.AppealId=b.ID
where
    Year=2024
    and AppealId >= 0
    and a.Remarks NOT LIKE '%SHaDE%Funds%'
    and UPPER(b.Name) like '%COVID%'
group by
    Year, AppealId;
