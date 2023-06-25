# JSON-RPC Micro Framework Boilerplate
## Purpose

This boilerplate project was born as a study purpose of both [Swoole](https://openswoole.com/) and the [JSON-RPC over HTTP](https://www.jsonrpc.org/historical/json-rpc-over-http.html) protocol. It is based on PHP 8.2 + Swoole 4.8 + MongoDB

### Idea of architecture
The idea is to use Swoole as a web server, where it will forward HTTP requests to the RPC handler, which will take care of translating the request into useful information for executing a specific rpc method. All under Controller Service Repository Architectural Pattern

![](Doc/architecture.png?raw=true "Architecture")

RPC methods are used as controllers, where they are responsible for executing the logic and translating the response back to the client

## Run
```sh
cp .env.example .env
docker-compose up
```
The database will be populated automatically at the first start of the container using the **initdb** of the MongoDB container

### Tests
Once the docker container is up:
```sh
docker exec -it swoole-php-1 composer test
```

## Usage
Once the web server is up you can make a call with Postman / Curl at:

`POST - http://127.0.0.1:9501` ( `9501` default port )

```json
{
  "jsonrpc": "2.0",
  "method": "getOilPriceTrend",
  "params": {
    "startDateISO8601": "2000-01-01",
    "endDateISO8601": "2000-01-05"
  },
  "id": 123
}
```
or as foreseen by the JSON-RPC protocol it is possible to make HTTP requests in GET encoding the parameters in base64
```
GET - http://127.0.0.1:9501?jsonrpc=2.0&method=getOilPriceTrend&params=ewogICAgInN0YXJ0RGF0ZUlTTzg2MDEiOiAiMjAwMC0wMS0wMSIsCiAgICAiZW5kRGF0ZUlTTzg2MDEiOiAiMjAwMC0wMS0wNSIKICB9&id=1
```

## Next steps
- Functional tests with Swoole
- Usage of ODM instead of native MongoDB php Client
- Usage of Swoole coroutine when is a notification RPC call
- Usage of Json-Schema to validate the RPC post body

## License

MIT

**Free Software**