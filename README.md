# ZagruzkaConnector

Библиотека предназначена для отправки сообщений посредством сервиса Zagruzka.com.

Сообщения отправляются посредством REST-протокола сервиса Zagruzka, подробности того, что означают поля в
объекте `Request` - см. в [документации](https://docs.zagruzka.com/pages/viewpage.action?pageId=24642131)
к сервису.

## TODO

Список нереализованных фич (будут доделываться по запросу):

1. Viber и Whatsapp сообщения (message/data/instantContent)
2. Дополнительные параметры Push-уведомлений
3. CascadeChainLink
4. ScheduleInfo
5. Классы Sender не только для СМС

## Самый простой вариант использования для отправки СМС

```php
    use RabotaRu\ZagruzkaConnector\Factory;
    use RabotaRu\ZagruzkaConnector\SMSSender;
    
    //Обычно CollectorRegistry у вас уже есть, вам надо получить его из вашего фреймворка.
    $registry = new \Prometheus\CollectorRegistry(
        new \Prometheus\Storage\APC(),
        false
    );
    
    //Получим стандартный коннектор
    $connector = (new Factory())->defaultConnector(
        "https://zagruzka.com/api",
        "zagruzka_sender",
        $registry
    );
     
     //И теперь сам отправщик
    $sender = new SMSSender(
        $connector,
        "login",
        "password",
        "MyService", //то, что будет отображаться как отправитель в СМС
        "https://myservice.com/callback_for_sms" //сюда будут приходить отчеты о доставке
    ); 
    
    $sender->sendSMS("+79261234567", "Test SMS");
    
```

## Hooks
Можно использовать хуки для дополнительной обработки в библиотеке (отправка событий статистики, логирование). Для этого надо реализовать соответствующие интерфейсы.

`RestPreSendHook` - выполняется перед отправкой данных, должны возвращать значение true или false. Если такой хук вернет false - отправки не будет
`RestPostSendHook` - выполняется после отправки данных, ничего не возвращает

## Request
Класс `RabotaRu\ZagruzkaConnector\RestRequest\Request` - это объектное представление запроса в API Zagruzka.com. (может не все поддерживать, см. **TODO**).

Пример того, как создается класс:
```php
new Request(
            $id,
            $this->login,
            $this->password,
            $destAddr,
            new RequestMessage(
                new RequestMessageType(),
                new RequestMessageData(
                    $text,
                    $this->serviceName
                )
            ),
            "https://myservice.com/callback_for_sms"
        );
```

## Response

Для того чтобы Response приходил, НЕОБХОДИМО указать notifyUrl в Request. Так же вы можете прописать такой URL в личном кабинете Zagruzka.com.

Можно настроить этот эндпоинт на запись метрик с помощью вашего коннектора - предлагаются три метода на выбор:
- у вас есть только строка в json: `processResponseByJson`
- ваш фреймворк вам уже все разобрал в массив: `processResponseByArray`
- ваш фреймворк вам может все разобрать в объект `RabotaRu\ZagruzkaConnector\RestResponse\Response` библиотеки - тогда воспользуйтесь `processResponse`


## Что еще можно расширять

Можно заменить транспорт — достаточно реализовать интерфейс `RabotaRu\ZagruzkaConnector\Transport\ITransport`, тогда при инстанцировании коннектора вы можете передать его.
Транспорт отправляет в заданный URL данные с помощью метода POST в формате json, так что его можно использовать и, например, для отправки событий в статистику.

Можно написать свой коллектор метрик — достаточно реализовать интерфейс `RabotaRu\ZagruzkaConnector\Metrics\IMetric`, тогда при инстанцировании коннектора вы можете передать его.

Можно отнаследовать коннектор и переопределить в нем константы-префиксы метрик. Создавать свою реализацию `IRestConnector` или переопределять метод `sendByRest` 
в `ZagruzkaConnector` не рекомендуется, так как именно там инкапсулирована вся логика записи метрик.

