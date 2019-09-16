# Symfony 4.1+ Rest Boilerplate
Boilerplate for starting Symfony 4.1+ Rest application with Auth0.
Docker is available also. (see `.docker` folder and `docker-compose.yml`)

## Used packages

- FOSRestBundle
- Auth0 security + Symfony Security
- JMS Serializer
- Doctrine ORM + annotations
- FrameworkExtraBundle (Symfony)

## Techniques
- Used DTO when mapping Request's payload
```php    
/**
 * @param int $id
 * @param CarDTO $carDTO
 * @return void
 * @Rest\Put("/cars/{id}")
 */
 public function updateCarAction(int $id, CarDTO $carDTO) {
     var_dump($carDTO);die;
 }
```