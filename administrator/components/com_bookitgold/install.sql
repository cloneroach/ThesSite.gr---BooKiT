-- -----------------------------------------------------
-- Table `#__bookitcategory`
-- -----------------------------------------------------

-- ----------
-- Thessite
-- Pinakas gia tis katigories dwmateiwn
-- Na baloume lchilds gia na upostirizei ilikies 6-12 ?
-- -----------
CREATE  TABLE IF NOT EXISTS `#__bookitcategory` (
  `idcategory` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` TEXT NULL ,
  `nguests` INT UNSIGNED NULL ,
  `nchilds` INT UNSIGNED NULL ,
  `cost` FLOAT UNSIGNED NULL ,
  `facilities` TEXT NULL ,
  `description` TEXT NULL ,
   `url` VARCHAR(45) NULL ,
  PRIMARY KEY (`idcategory`) )
PACK_KEYS = 0
ROW_FORMAT = DEFAULT
ENGINE=MyISAM 
AUTO_INCREMENT=0
DEFAULT CHARSET=utf8;



-- -----------------------------------------------------
-- Table `#__bookitextra`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__bookitextra` (
  `idextra` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` TEXT NULL DEFAULT NULL ,
  `extra_type` INT UNSIGNED NULL DEFAULT NULL COMMENT '1=required; 0=optional' ,
  `value_fix` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `value_percent` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `value_type` INT UNSIGNED NULL DEFAULT NULL COMMENT '1=per booking; 2=per day; 3=per person; ' ,
  `description` TEXT NULL ,
  PRIMARY KEY (`idextra`) )
ENGINE = MyISAM
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8
PACK_KEYS = 0
ROW_FORMAT = DEFAULT;



-- -----------------------------------------------------
-- Table `mydb`.`#__bookitroom`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__bookitroom` (
  `idroom` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idcategory` INT UNSIGNED NULL DEFAULT NULL ,
  `name` TEXT NULL DEFAULT NULL ,
  `description` TEXT NULL ,
  `url` VARCHAR(45) NULL ,
  PRIMARY KEY (`idroom`) ,
  INDEX `idcategory` (`idcategory` ASC) ,
  CONSTRAINT `idcategory`
    FOREIGN KEY (`idcategory` )
    REFERENCES `mydb`.`#__bookitcategory` (`idcategory` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8
PACK_KEYS = 0
ROW_FORMAT = DEFAULT;

-- -----------------------------------------------------
-- Table `#__bookitcoupon`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__bookitcoupon` (
  `idcoupon` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` TEXT NULL DEFAULT NULL ,
  `value_fix` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `value_percent` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `usable` INT UNSIGNED NULL DEFAULT NULL ,
  `used` INT UNSIGNED NULL DEFAULT NULL ,
  `valid_from` DATE NULL DEFAULT NULL ,
  `valid_to` DATE NULL DEFAULT NULL ,
  `code` TEXT NULL DEFAULT NULL ,
  `every_x_day` INT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`idcoupon`) )
ENGINE = MyISAM
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8
PACK_KEYS = 0
ROW_FORMAT = DEFAULT;


-- -----------------------------------------------------
-- Table `#__bookitcountry`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__bookitcountry` (
  `idcountry` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` TEXT NULL ,
  PRIMARY KEY (`idcountry`) )
PACK_KEYS = 0
ROW_FORMAT = DEFAULT
ENGINE=MyISAM 
AUTO_INCREMENT=0
DEFAULT CHARSET=utf8;

INSERT INTO `#__bookitcountry` (`idcountry`, `name`) VALUES 
('AF', 'Afghanistan'),
('AX', 'Aland Islands'),
('AL', 'Albania'),
('DZ', 'Algeria'),
('AS', 'American Samoa'),
('A0', 'Angola'),
('AI', 'Anguilla'),
('AQ', 'Antarctica'),
('AG', 'Antigua and Barbuda'),
('AR', 'Argentina'),
('AM', 'Armenia'),
('AW', 'Aruba'),
('AU', 'Australia'),
('AT', 'Austria'),
('AZ', 'Azerbaijan'),
('BS', 'Bahamas'),
('BH', 'Bahrain'),
('BD', 'Bangladesh'),
('BB', 'Barbados'),
('BY', 'Belarus'),
('BE', 'Belgium'),
('BZ', 'Belize'),
('BJ', 'Benin'),
('BM', 'Bermuda'),
('BT', 'Bhutan'),
('BO', 'Bolivia'),
('BA', 'Bosnia and Herzegovina'),
('BW', 'Botswana'),
('BV', 'Bouvet Island'),
('BR', 'Brazil'),
('BN', 'Brunei Darussalam'),
('BG', 'Bulgaria'),
('BF', 'Burkina Faso'),
('BI', 'Burundi'),
('KH', 'Cambodia'),
('CM', 'Cameroon'),
('CA', 'Canada'),
('CV', 'Cape Verde'),
('KY', 'Cayman Islands'),
('CF', 'Central African Rep'),
('TD', 'Chad'),
('CL', 'Chile'),
('CN', 'China'),
('CX', 'Christmas Island'),
('CC', 'Cocos Islands '),
('CO', 'Colombia'),
('KM', 'Comoros'),
('CD', 'Congo'),
('CK', 'Cook Islands'),
('CR', 'Costa Rica'),
('CI', 'Cote d Ivoire'),
('HR', 'Croatia'),
('CU', 'Cuba'),
('CY', 'Cyprus'),
('CZ', 'Czech Republic'),
('DK', 'Denmark'),
('DJ', 'Djibouti'),
('DM', 'Dominica'),
('DO', 'Dominican Rep'),
('EC', 'Ecuador'),
('EG', 'Egypt'),
('SV', 'El Salvador'),
('CG', 'Equatorial Guinea'),
('ER', 'Eritrea'),
('EE', 'Estonia'),
('ET', 'Ethiopia'),
('FK', 'Falkland Islands'),
('FO', 'Faroe Islands'),
('FJ', 'Fiji'),
('FI', 'Finland'),
('FR', 'France'),
('GF', 'French Guiana'),
('PF', 'French Polynesia'),
('GA', 'Gabon'),
('GM', 'Gambia'),
('GE', 'Georgia'),
('DE', 'Germany'),
('GH', 'Ghana'),
('GI', 'Gibraltar'),
('GR', 'Greece'),
('GL', 'Greenland'),
('GD', 'Grenada'),
('GP', 'Guadeloupe'),
('GU', 'Guam'),
('GT', 'Guatemala'),
('GG', 'Guernsey'),
('GN', 'Guinea'),
('GW', 'Guinea-Bissau'),
('GY', 'Guyana'),
('HT', 'Haiti'),
('VA', 'Holy See'),
('HN', 'Honduras'),
('HK', 'Hong Kong'),
('HU', 'Hungary'),
('IS', 'Iceland'),
('IN', 'India'),
('ID', 'Indonesia'),
('IR', 'Iran'),
('IQ', 'Iraq'),
('IE', 'Ireland'),
('IM', 'Isle of Man'),
('IL', 'Israel'),
('IT', 'Italy'),
('JM', 'Jamaica'),
('JP', 'Japan'),
('JE', 'Jersey'),
('JO', 'Jordan'),
('KZ', 'Kazakhstan'),
('KE', 'Kenya'),
('KI', 'Kiribati'),
('PRK', 'Korea PRK'),
('KOR', 'Korea'),
('KW', 'Kuwait'),
('KG', 'Kyrgyzstan'),
('LA', 'Lao'),
('LV', 'Latvia'),
('LB', 'Lebanon'),
('LS', 'Lesotho'),
('LY', 'Libya'),
('LT', 'Liechtenstein'),
('LU', 'Luxembourg'),
('MO', 'Macao'),
('MK','FYROM'),
('MG','Madagascar'),
('MW','Malawi'),
('MY','Malaysia'),
('MV','Maldives'),
('ML','Mali'),
('MT','Malta'),
('MH','Marshall Islands'),
('MQ','Martinique'),
('MR','Mauritania'),
('MU','Mauritius'),
('YT','Mayotte'),
('MX','Mexico'),
('FM','Micronesia'),
('MD','Moldova'),
('MC','Monaco'),
('MN','Mongolia'),
('ME','Montenegro'),
('MS','Montserrat'),
('MA','Morocco'),
('MZ','Mozambique'),
('MM','Myanmar'),
('NA','Namibia'),
('NR','Nauru'),
('NP','Nepal'),
('NL','Netherlands'),
('AN','Netherlands Antilles'),
('NC','New Caledonia'),
('NZ','New Zealand'),
('NI','Nicaragua'),
('NE','Niger'),
('NG','Nigeria'),
('NU','Niue'),
('NF','Norfolk Island'),
('NO','Norway'),
('OM','Oman'),
('PK','Pakistan'),
('PW','Palau'),
('PA','Panama'),
('PG','Papua New Guinea'),
('PY','Paraguay'),
('PE','Peru'),
('PH','Philippines'),
('PN','Pitcairn'),
('PL','Poland'),
('PT','Portugal'),
('PR','Puerto Rico'),
('QA','Qatar'),
('RE','Reunion'),
('RO','Romania'),
('RU','Russian Federation'),
('RW','Rwanda'),
('BL','Saint Barthélemy'),
('SH','Saint Helena'),
('KN','Saint Kitts & Nevis'),
('LC','Saint Lucia'),
('MF','Saint Martin'),
('WS','Samoa'),
('SM','San Marino'),
('ST','Sao Tome'),
('SA','Saudi Arabia'),
('SN','Senegal'),
('RS','Serbia'),
('SC','Seychelles'),
('SL','Sierra Leone'),
('SG','Singapore'),
('SK','Slovakia'),
('SI','Slovenia'),
('SB','Solomon Islands'),
('SO','Somalia'),
('ZA','South Africa'),
('ES','Spain'),
('LK','Sri Lanka'),
('SD','Sudan'),
('SR','Suriname'),
('SZ','Swaziland'),
('SE','Sweden'),
('CH','Switzerland'),
('SY','Syria'),
('TW','Taiwan'),
('TJ','Tajikistan'),
('TZ','Tanzania'),
('TH','Thailand'),
('TL','Timor-Leste'),
('TG','Togo'),
('TK','Tokelau'),
('TO','Tonga'),
('TT','Trinidad & Tobago'),
('TN','Tunisia'),
('TR','Turkey'),
('TM','Turkmenistan'),
('TV','Tuvalu'),
('UG','Uganda'),
('UA','Ukraine'),
('AE','Arab Emirates'),
('GB','United Kingdom'),
('US','United States'),
('UY','Uruguay'),
('UZ','Uzbekistan'),
('VU','Vanuatu'),
('VE','Venezuela'),
('VN','Viet Nam'),
('YE','Yemen'),
('ZM','Zambia'),
('ZW','Zimbabwe');
				

-- -----------------------------------------------------
-- Table `#__bookitguests`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__bookitguests` (
  `idguests` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idcountry` INT UNSIGNED NULL DEFAULT NULL ,
  `name` TEXT NULL DEFAULT NULL ,
  `surname` TEXT NULL DEFAULT NULL ,
  `email` TEXT NULL DEFAULT NULL ,
  `city` TEXT NULL DEFAULT NULL ,
  `addr` TEXT NULL DEFAULT NULL ,
  `phone` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`idguests`) ,
  INDEX `guests_FKIndex1` (`idcountry` ASC) ,
  INDEX `idcountry` (`idcountry` ASC) ,
  CONSTRAINT `idcountry`
    FOREIGN KEY (`idcountry` )
    REFERENCES `mydb`.`#__bookitcountry` (`idcountry` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8
PACK_KEYS = 0
ROW_FORMAT = DEFAULT;


-- -----------------------------------------------------
-- Table `#__bookitbooking`
-- -----------------------------------------------------

-- -------------
-- Thessite
-- Pinakas gia kratiseis (?)
-- Na baloume lchilds gia na apo8ikeuei posa paidia 6-12 8a exei i kratisi?
-- -------------

CREATE  TABLE IF NOT EXISTS `#__bookitbooking` (
  `idbook` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idcoupon` INT UNSIGNED NOT NULL ,
  `idroom` INT UNSIGNED NULL ,
  `idcategory` INT UNSIGNED NULL ,
  `idguests` INT UNSIGNED NULL ,
  `nguests` INT UNSIGNED NULL ,
  `value_paid` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `value_pending` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `value_full` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `valid_from` DATE NULL DEFAULT NULL ,
  `valid_to` DATE NULL DEFAULT NULL ,
  `today` DATE NULL DEFAULT NULL ,
  `extra_ids` TEXT NULL DEFAULT NULL ,
  `nchilds` INT UNSIGNED NULL ,
  `preferences` TEXT NULL DEFAULT NULL ,
  `status` INT UNSIGNED NULL ,
  PRIMARY KEY (`idbook`) ,
  INDEX `booking_FKIndex1` (`idcoupon` ASC) ,
  INDEX `booking_FKIndex2` (`idguests` ASC) ,
  CONSTRAINT `fk_{A80D650A-D332-468E-A00C-6CDCA3860F92}`
    FOREIGN KEY (`idcoupon` )
    REFERENCES `coupon` (`idcoupon` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_{8C046A06-5764-4F81-B1BD-89A9E3B46EA5}`
    FOREIGN KEY (`idguests` )
    REFERENCES `guests` (`idguests` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
PACK_KEYS = 0
ROW_FORMAT = DEFAULT
ENGINE=MyISAM 
AUTO_INCREMENT=0
DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table `#__bookitspecialoffer`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__bookitspecialoffer` (
  `idoffer` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` TEXT NULL ,
  `value_fix` FLOAT UNSIGNED NULL ,
  `value_percent` FLOAT UNSIGNED NULL ,
  PRIMARY KEY (`idoffer`) )
PACK_KEYS = 0
ROW_FORMAT = DEFAULT
ENGINE=MyISAM 
AUTO_INCREMENT=0
DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table `#__bookitavailability`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__bookitavailability` (
  `idavailability` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idoffer` INT UNSIGNED NOT NULL ,
  `idbook` INT UNSIGNED NOT NULL ,
  `idroom` INT UNSIGNED NULL ,
  `today` DATE NULL ,
  `price` FLOAT UNSIGNED NULL ,
  `price_deviation_1` INT UNSIGNED NULL ,
  `price_deviation_2` INT UNSIGNED NULL ,
  `availability` INT UNSIGNED NULL ,
  `idcategory` INT UNSIGNED NULL ,
  PRIMARY KEY (`idavailability`) ,
  INDEX `avaliability_FKIndex1` (`idroom` ASC) ,
  INDEX `availability_FKIndex2` (`idbook` ASC) ,
  INDEX `availability_FKIndex3` (`idoffer` ASC) ,
  CONSTRAINT `fk_{00CA796B-10FC-49A9-B132-5E06B06BCEF7}`
    FOREIGN KEY (`idroom` )
    REFERENCES `room` (`idroom` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_{FC4C5345-F1C9-4B7F-94B2-1C336EE54C66}`
    FOREIGN KEY (`idbook` )
    REFERENCES `booking` (`idbook` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_{69034FB5-7385-473D-874A-44B08EB473A3}`
    FOREIGN KEY (`idoffer` )
    REFERENCES `specialoffer` (`idoffer` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
PACK_KEYS = 0
ROW_FORMAT = DEFAULT
ENGINE=MyISAM 
AUTO_INCREMENT=0
DEFAULT CHARSET=utf8;
