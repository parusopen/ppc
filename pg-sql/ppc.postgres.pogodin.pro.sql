/* про раздел */
SELECT * FROM v_unitlist WHERE sunitcode='BankDocuments';
/* представление раздела */
SELECT *
FROM V_DMSCLVIEWS
WHERE nprn IN
  (SELECT nrn FROM v_unitlist WHERE sunitcode='BankDocuments'
  )
AND naccessibility=1;
SELECT NRN,
  SVIEW_NAME
FROM V_DMSCLVIEWS
WHERE nprn IN
  (SELECT nrn FROM v_unitlist WHERE sunitcode='BankDocuments'
  )
AND naccessibility=1
ORDER BY nrn limit 1;
/* все атрибуты представления */
SELECT *
FROM V_DMSCLVIEWSATTRS
WHERE nprn IN
  (SELECT NRN
  FROM V_DMSCLVIEWS
  WHERE nprn IN
    (SELECT nrn FROM v_unitlist WHERE sunitcode='BankDocuments'
    )
  AND naccessibility=1
  ORDER BY nrn limit 1
  );
/* обязательные поля ограничений */
SELECT nattribute
FROM V_DMSCLCONATTRS
WHERE nprn IN
  (SELECT NRN FROM V_DMSCLCONSTRS WHERE NPRN=485 AND NCONSTRAINT_TYPE=5
  )
--
--
/* обязательные атрибуты представления */
SELECT *
FROM V_DMSCLVIEWSATTRS,
  (SELECT nrn FROM v_unitlist WHERE sunitcode='BankDocuments'
  ) UL
WHERE nprn IN
  (SELECT NRN
  FROM V_DMSCLVIEWS
  WHERE nprn       = UL.NRN
  AND naccessibility=1
  ORDER BY nrn limit 1
  )
AND nattr IN
  (SELECT nattribute
  FROM V_DMSCLCONATTRS
  WHERE nprn IN
    (SELECT NRN
    FROM V_DMSCLCONSTRS
    WHERE NPRN          = (UL.NRN )
    AND NCONSTRAINT_TYPE=5
    )
  )
  AND   nforeign_key=0
  order by NPOSITION;
