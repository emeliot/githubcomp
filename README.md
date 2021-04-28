# githubcomp

To install please just pull repository and then run:
```
docker-compose up -d
```
it containe docker image with symfony framework. You should se welcome page under:
```
http://localhost:8000/
```

For testing API you can use attached postman collection, with example data attached 
``
postman_collection_githubcomp.json
``

There are only two endpoints, first is to just pull information about repository
```
[GET] http://localhost:8000/api/repositories/owner/{ownerName}/package/{packageName}
```
Second is more complex
```
[POST] http://localhost:8000/api/repositories/compare
```
You can compare as many repositories you want. Just add repository name as `symfony/symfony` to for-data body uder `compare[]` array key.
