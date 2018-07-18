# Blbulyandavbulyan-Web-Site-Engine
В этом репозиторри лежит движок моего сайта, для его работы требуеться создать базу данных MySQL,
файл с необходимыми таблицами, для создания её структуры будет в этом же репозитории и называеться он tables.sql
Запустите его и он создаст необходимую структуру БД.

## Уровни организации контента контента:
1. Категория.
2. Подкатегория.
3. Подподкатегории.
4. Канал.
Категория - самый верхний уровень, с ним связаны подкатегории.
Подкатегория - второй уровень, с ним связаны подподкатегории.
Подподкатегория - третий уровень, с ним связаны каналы.
Канал(некий ресурс) - самый нижний уровень, он связан с категорией, подкатегорией, подподкатегорией.
Что и как с чем связано - это храниться в БД, у каждого уровня есть столбец в БД с идентификатором, у уровней начиная 
с подкатегории есть столбецы в БД с идентификаторами родительских уровней, у подкатегории например - один столбец в БД содержит
ИД родительсокй категории, у подподкатегории есть два столбца, один содержит ИД родительской категории,
другой содержит ИД родительской подкатегории, у канала таких столбцов три - один содержит ИД родительсокй категории,
другой ИД родительсокй подкатегории, третий ИД родительсокй подподкатегории.
При добавлении контента любого уровня ему назначаеться УНИКАЛЬНЫЙ идентификатор (ОН ОБЯЗАН БЫТЬ УНИКАЛЬНЫМ),
имя также должно быть уникальным.
У каждого уровня существуют ещё два параметра которые ДОЖНЫ быть уникальными - это имя уровня, и ссылка,
у категори и у канала ссылка должна быть обязательно.
## Файл index.php 
Этот файл являеться главной страницей сайта, предназначен для отображения категорий, в HTML теге <nav></nav> появяться ссылки 
атрибут href этих ссылок будет иметь следующее значение show.php?category_id=ИДК, где show.php - файл для отображения контента, ИДК - это идентификатор категории, контент которой нужно показать.
## Файл show.php
Данный файл предназначен для отображений контента, он принимает следующие GET параметры:
1. category_id - ИД категории, контент которой нужно отобразить.
2. subcategory_id - ИД подкатегории, контент которой нужно отобразить (пока не реализованно).
3. subsubcategory_id - ИД подподкатегории которую нужно отобразить (пока не реализованно).
4. channel_id - ИД канала, о котором нужно получить информацию. (пока не реализованно).
#ВНИМАНИЕ ДАННЫЕ ФАЙЛЫ ТРЕБУЮТ АВТОРИЗАЦИИ, НО АВТОРИЗАЦИЯ В НИХ НЕ РЕАЛИЗОВАННА!
## Файл addchannels.php
Этот файл предназначен для добавления контента, при открытии вас попросят загрузить специальный html файл
(примеры таких файлов будут в этом же репозитории в папке htmlfilesforadd).
Данный файл работоспособен.
## Файл addchannel.php 
Предназначен для ручного добавления контента
(в репозитории присутсвует, но пока не реализован до конца, открывать его клиентам не следует)
