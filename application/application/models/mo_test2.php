,DATE_FORMAT(cf_GrandFDateOfDeath,'{$convertFormat}') AS cf_GrandFDateOfDeath,DATE_FORMAT(cf_GrandFDOB,'{$convertFormat}') AS cf_GrandFDOB,p2.CountryDes AS fgpCountry,p3.description AS fgpArea,p6.description AS fgpJobDes,DATE_FORMAT(cf_GrandFBCDate,'{$convertFormat}') AS cf_GrandFBCDate,DATE_FORMAT(cf_GrandFDCDate,'{$convertFormat}') AS cf_GrandFDCDate
,DATE_FORMAT(cf_GrandMDateOfDeath,'{$convertFormat}') AS cf_GrandMDateOfDeath,DATE_FORMAT(cf_GrandMDOB,'{$convertFormat}') AS cf_GrandMDOB,p8.CountryDes AS fgmCountry,p9.description AS fgmArea,p1.description AS fgmJobDes,DATE_FORMAT(cf_GrandMBCDate,'{$convertFormat}') AS cf_GrandMBCDate,DATE_FORMAT(cf_GrandMDCDate,'{$convertFormat}') AS cf_GrandMDCDate
,DATE_FORMAT(cm_GrandFDateOfDeath,'{$convertFormat}') AS cm_GrandFDateOfDeath,DATE_FORMAT(cm_GrandFDOB,'{$convertFormat}') AS cm_GrandFDOB,p4.CountryDes AS mgpCountry,p5.description AS mgpArea,p7.description AS mgpJobDes,DATE_FORMAT(cm_GrandFBCDate,'{$convertFormat}') AS cm_GrandFBCDate,DATE_FORMAT(cm_GrandFDCDate,'{$convertFormat}') AS cm_GrandFDCDate
,DATE_FORMAT(cm_GrandMDateOfDeath,'{$convertFormat}') AS cm_GrandMDateOfDeath,DATE_FORMAT(cm_GrandMDOB,'{$convertFormat}') AS cm_GrandMDOB,p6.CountryDes AS mgmCountry,p10.description AS mgmArea,p11.description AS mgmJobDes,DATE_FORMAT(cm_GrandMBCDate,'{$convertFormat}') AS cm_GrandMBCDate,DATE_FORMAT(cm_GrandMDCDate,'{$convertFormat}') AS cm_GrandMDCDate