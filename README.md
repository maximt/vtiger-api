## 1. run project
### get sources
```
git clone git@github.com:maximt/vtiger-api.git
cd vtiger-api
```
### copy and edit .env file:
```
cp .env.example .env
```
### run container
```
docker-compose up
```

---

## 2. sql queries 
### query to get contacts with tags
```
SELECT 
    c.`contactid`, c.`lastname`, c.`firstname`, c.`phone`, 
    GROUP_CONCAT(cf.`cf_1107`) AS tags
FROM `vtiger_contactdetails` c 
INNER JOIN `vtiger_contactscf` ccf ON c.`contactid` = ccf.`contactid`
LEFT JOIN `vtiger_cf_1107` cf ON FIND_IN_SET(cf.`cf_1107id`, REPLACE(ccf.`cf_1107`, ' |##| ', ',')) > 0
GROUP BY c.`contactid`;
```
