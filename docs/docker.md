## Running PBBoard using docker

### prerequisites
- [Docker](https://www.docker.com/products/docker-desktop/)
- [Docker Compose](https://docs.docker.com/compose/gettingstarted/)

## installation
- first of all copy `.env.example` file with name `.env` and set your preferred environment variables
- simply run :
```bash
 docker-compose up --build
```

### usage
to access your *PBBoard* Forum use `hhttp://localhost`
to change  default port  change APP_PORT value in `.env` file
to access PhpMyAdmin use `hhttp://localhost:8080`