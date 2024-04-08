# Popo
A Popo (Plain old PHP object) is our version of the Pojo (Plain old Java object). The goal of 
these objects is to replace all variables that would otherwise be key-value arrays with strictly typed PHP objects.

This package only contains a single class (BasePopo) which is intended to be extended by every Popo we create. The 
BasePopo class ensures that you can return Popo's from your back-end to your front-end, and that they will automatically be converted to JSON without you having to write code for this. Besides that, the BasePopo provides all Popo's with the `toArray()` function.

## Installation

```sh
composer require scrumble-nl/popo
```

## Usage
When creating a new Popo object simply extend BasePopo. Only public attributes will be included when converting to an array or
when returning the Popo to your front-end, it is therefore required you mark visible attributes as public.

### Basic usage
```php
class ProductPopo extends BasePopo
{    
    /**
     * @var string
     */
    public string $name;

    /**
     * @var float
     */
    public float $price;

    /**
     * @param string $name
     * @param float  $price
     */
    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }
}
```

### Initialization

To instantiate the Popo, you could either use the constructor as you are already used to, or develop a reusable helper function in the Popo itself. This function should take into account where the input data is coming from, and in what format that data is.

For example, a typical API response will be decoded JSON, as an object or an array. The function to instantiate the Popo would then be as follows:
```php
public static function fromApiResponse(array $apiResponse): ProductPopo
{
    return new self(
        $apiResponse['name'],
        (float) $apiResponse['price'],
    );
}
```
The Popo may then be instantiated from any place within your project by calling this function: `ProductPopo::fromApiResponse($array);`.

You are free to implement other methods, such as a `fromObject`, or `fromArray` whichever suits your case best.

### Strict typing and sub-Popo's

You may also have cases where your Popo's have their own sub-Popo's. For example, a `ProductPopo` may have a property `public Collection $categories`, which would then be an Collection of `CategoryPopo`'s.

To ensure optimal usage of strict typing when working with sub-Popo's in arrays or Collections, make sure you typehint these in your PHPDoc:
```php
/**
* @var Collection<int, CategoryPopo>
*/
public Collection $categories;
```

## Testing

When writing tests for your application, you may find it useful to create instances of your Popo objects. One way to do this is by using factories.

A factory is a class that is responsible for creating instances of another class. In the context of Popo objects, a factory can be used to create instances of your Popo objects with random or specific values, which can be very useful for testing.

### Testing usage

First, create a factory class for your Popo object. The factory class should have a method that returns an instance of your Popo object with random or specific values. Here's an example factory class for the ProductPopo object.

```php
use Tests\Popo\ProductPopo;
use Scrumble\Popo\PopoFactory;

class ProductPopoFactory extends PopoFactory
{
    /**
     * @var null|string
     */
    public ?string $popoClass = ProductPopo::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->randomNumber(1, 10),
        ];
    }
}
```

Then, make sure you include the factory in your Popo's definition.


```php
use Scrumble\Popo\HasPopoFactory;

class ProductPopo extends BasePopo
{
    use HasPopoFactory;
    
    /**
     * @var string
     */
    public string $name;

    /**
     * @var float
     */
    public float $price;

    /**
     * @param string $name
     * @param float  $price
     */
    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }
    
    /**
     * @return string
     */
    public static function popoFactory(): string
    {
        return ProductPopoFactory::class;
    }
}
```

### Factory create

Let's say you have a service function `create` that takes in a ProductPopo object and creates a new product based on its properties. You can use Popo factories to easily create test data for this function:

```php
/** @test */
public function can_create_product(): void
{
    $productPopo = ProductPopo::factory()->create();

    $product = $this->productService->create($productPopo);

    $this->assertDatabaseHas('products', [
        'name' => $productPopo->name,
        'price' => $productPopo->price,
    ]);
}
```

### Create multiple

Sometimes you may want to create multiple instances of a Popo with slightly different attributes in your unit tests. You can achieve this using the `count()` and `sequence()` methods provided by the package.

The `count(int $count)` method takes an integer as its argument and tells the factory how many instances to create. The `sequence(array|closure $sequense)` method takes an array of attribute values for each instance to create. You can also provide a closure to the `sequence()` method that returns an array of attribute values.

Here's an example of how you can use `count()` and `sequence()` to create multiple instances of a Popo with slightly different attributes:

```php
ProductPopo::factory()
    ->count(3)
    ->sequence(
        ['name' => 'Apples'],
        ['name' => 'Cookies'],
        ['name' => 'Bread'],
    )
    ->create();
```

> Note that passing attributes to the `create()` method will override values passed in `sequence()`.

### Raw data

The `raw(array $attributes = [])` function returns an array with the attributes that will be used to create an array with the values from the factory.

```php
$productData = ProductPopo::factory()->raw();
```

### Overriding data

The `state()` function is a powerful feature of the Laravel factories that allows you to define a set of attributes that will override the default values of the factory. This can be useful when you need to create a Popo with a specific set of attributes that cannot be generated by the default factory.

Here's an example of how you can use the `state()` function to set the state of a Popo:

```php
class ProductPopoFactory extends Factory
{
    ...

    public function published(): array
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => true,
            ];
        });
    }
}
```

In this example, we have defined a factory for the `ProductPopo` class with a default set of attributes that includes a `published` attribute set to false. We have also defined a `published()` function that uses the `state()` function to set the `published` attribute to true.

Now, let's say we want to create a Popo that is published. We can do this by calling the published() function on the factory:

```php
$popo = ProductPopo::factory()->published()->create();
```

You can override specific data by passing an array of attribute values to the `create()` function. The array should contain the attribute names as keys and the desired values as their corresponding values. The values in the array will override any default values and any values that have been set using the `state()` function.

```php
$popo = ProductPopo::factory()->create([
    'name' => 'New product',
]);
```

In the above example, we are creating a new ProductPopo instance and overriding the default name value with our own value.

Similarly, you can also override specific data using the `raw()` function. By passing an array of attribute values to the `raw()` function, the values will override any default values and any values that have been set using the `state()` function.

```php
$productData = ProductPopo::factory()->raw([
    'name' => 'New product',
]);
```

In the above example, we are creating a new array of ProductPopo instance attributes using the `raw()` function and overriding the default name value with our own value.

## Contributing
If you would like to see additions/changes to this package you are always welcome to add some code or improve it.

## Scrumble
This product has been originally developed by [Scrumble](https://www.scrumble.nl) for internal use. As we have been using lots of open source packages we wanted to give back to the community. We hope this helps you getting forward as much as other people helped us!
