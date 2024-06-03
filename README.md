
# test dev symfony

## Run Locally

Clone the project

```bash
  git@github.com:anisbouazizi/test-dev-symfony.git
```

Run the docker-compose

```bash
  docker-compose up -d
```

Log into the PHP container

```bash
  docker exec -it www_docker_api
```

Inside the php container, run composer install
```bash
 composer install
```


*Your application is available at http://localhost:8741/*



## Author

- [@yanisbouazizi](https://github.com/anisbouazizi)
