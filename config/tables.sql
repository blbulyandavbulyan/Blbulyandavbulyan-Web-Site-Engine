/*
Copyright Blbulyan Software
This software is distributed under the GNU GPL v3
This software was developed by one Russian schoolboy.
All proposals for its development are accepted, criticism is welcomed.
*/
CREATE TABLE channels(
	`ChannelID` int NOT NULL AUTO_INCREMENT,/*ИД канала*/
    `CategoryID` int,/*ИД категории соответствующей данному каналу*/
	`SubcategoryID` int,/*ИД подкатегории соответствующей данному каналу*/
	`SubsubcategoryID` int, /*ИД подподкатегории соответствующей данному каналу*/
    `ChannelName` varchar(500) NOT NULL,/*Имя канала*/
    `ChannelLink` varchar(500) NOT NULL, /*Ссылка на канал*/
	`Description` varchar(1000), /*Описание канала */
    PRIMARY KEY (ChannelID)
);
CREATE TABLE categories(
	`CategoryID` int NOT NULL AUTO_INCREMENT, /*ИД категории*/
	`CategoryName` varchar(255) NOT NULL,/*Имя категории*/
	`CategoryLink` varchar(500) NOT NULL, /*Ссылка на категорию*/
	`CategoryDescription` varchar(500), /*Описание категории*/
	`HTMLClass` varchar(255), /*HTML класс для категории*/
	`HTMLid` varchar(255), /*HTML ИД для категории*/
	`CategoryIconHref` varchar(255), /*Путь до файла иконки категории*/
	`CategoryStyleHref` varchar(255),/*Путь файла стилей катешгории */
	PRIMARY KEY (CategoryID)
);
CREATE TABLE subcategories(
	`SubcategoryID` int NOT NULL AUTO_INCREMENT, /*ИД подкатегории*/
	`CategoryID` int, /*ИД надкатегории*/
	`SubcategoryName` varchar(255) NOT NULL,/*Имя подкатегории*/
	`SubcategoryLink` varchar(500) NOT NULL,/*Ссылка на подкатегорию*/
	`HTMLClass` varchar(255), /*HTML класс для подкатегории*/
	`HTMLid` varchar(255), /*HTML ИД для подкатегории*/
	`HRClass` varchar(255),/*Класс горизонтальной черты которая идёт после данной подкатегории*/
	`HRid` varchar(255),/*ИД горизонтальной черты которая идёт после данной подкатегории*/
	PRIMARY KEY (SubcategoryID)
);
CREATE TABLE subsubcategories(
	`SubsubcategoryID` int NOT NULL AUTO_INCREMENT, /*ИД подподкатегории */
	`SubcategoryID` int, /*ИД надкатегории*/
	`CategoryID` int, /*ИД категории (самая верхняя)*/
	`SubsubcategoryName` varchar(255) NOT NULL,/*Имя подподкатегории*/
	`SubsubcategoryLink` varchar(500) NOT NULL,/*Ссылка на подподкатегорию*/
	`HTMLClass` varchar(255), /*HTML класс для подподкатегории*/
	`HTMLid` varchar(255), /*HTML ИД для подподкатегории*/
	`HRClass` varchar(255),/*Класс горизонтальной черты которая идёт после данной подподкатегории*/
	`HRid` varchar(255),/*ИД горизонтальной черты которая идёт после данной подподкатегории*/
	PRIMARY KEY (SubsubcategoryID)
);
/*Таблица с сообщениями*/
CREATE TABLE messages(
	`MessageID` int NOT NULL AUTO_INCREMENT,
	`SenderUID` int,
	`MessageType` varchar(255),
	`MessageText` varchar(1000),
	`DateAndTimeSend` TIMESTAMP,
	 PRIMARY KEY(MessageID)
);
/*таблица с пользователями*/
CREATE TABLE users(
	`UID` int NOT NULL AUTO_INCREMENT,/*ИД пользователя*/
	`UserName` varchar(255) NOT NULL, /*Имя пользователя в системе*/
	`FirstName` varchar(255) NOT NULL,/*Реальное имя пользователя*/
	`LastName` varchar(255) NOT NULL,/*Реальная фамилия пользователя*/
	`UserEmail` varchar(500) NOT NULL,/*Почта пользователя*/
	`UserPassword` varchar(1000) NOT NULL,/*Пароль пользователя*/
	`Privilege` varchar(255), /*Строка с превилегией пользователя*/
	`TokenID` varchar(1000) NOT NULL,/*ИД токена с которым пользователь зарегистрировался на сайте*/
	`DateAndTimeOfUserCreation` TIMESTAMP,/*Дата и время создания пользователя*/
	`TwoFactorAuthentication` varchar(255), /*Тип двухфакторной аунтетификации на данный момент может быть Email и отсутствовать, если двухфакторная аунтетификация отсутствует тогда это поле равно NONE*/
	PRIMARY KEY (UID)
);
/*Таблица с токенами*/
CREATE TABLE tokens(
	`TokenID` int NOT NULL AUTO_INCREMENT,/*ИД токена*/
	`TokenForUserName` varchar(255) NOT NULL,/*Имя пользователя для которого предназначен токен*/
	`TokenForEmail` varchar(500) NOT NULL,/*Email пользователя для которого предназначен токен*/
	`TokenPrivilege` varchar(255) NOT NULL,/*Привелегия которая будет выданна пользователю*/
	`CreatorUID` int, /*UID создавшего токен*/
	`Token` varchar(1000) NOT NULL,/*Сам токен*/
	`TokenIsUsedWithIP` int unsigned,/*IP использовавшего токен*/
	`TokenUsedWithUserAgent` varchar(500),/*UserAgent использовавшего токен*/
	`DateAndTimeOfUse` TIMESTAMP,/*Дата и время использования токена*/
	`DateAndTimeOfTokenCreation` TIMESTAMP,/*дата и время создания*/
	`IsTheTokenUsed` boolean,/*Использован ли токен*/
	PRIMARY KEY (TokenID) 
);
CREATE TABLE logs(
	`EventID` int NOT NULL AUTO_INCREMENT,
	`EventText` varchar(1000) NOT NULL,
	`
);
