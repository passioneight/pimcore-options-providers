# Usage
Options providers support different input and/or provide different output - depending on the use case.
The most common use case is a Pimcore-`select`, which provides values that are defined in a constant class (e.g., `Availability`). 

> When using this bundle, it does not matter if a single- or multi-select is used - i.e., the options will always be
> displayed correctly with the same implementation.

### ConstantOptionsProvider
The `ConstantOptionsProvider` provides options based on the field name in the class definition. Specifically, the field
name is used to search for a constant class in `App\Constant\<fieldname>`. To leverage this functionality, you'll have to:

1. create your constant class,
2. add `@Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\OptionsProvider\ConstantOptionsProvider` to your class definition.

> The `@` in the second step is important, as we want to use the `OptionsProvider` as service.

Assume that a `Product` class definition exists and the `OptionsProvider` was set as described before - for the field
`availability`. The `ConstantOptionsProvider` will automatically look for the class `App\Constant\Availability`, as this was inferred
from the field name. If a class was found, the values of the class are used - provided that a `getAll` method was implemented.

> Using the constant-class of our [Php Utilities Bundle](https://github.com/passioneight/php-utilities), the method will be available automatically.

##### Translating Options
Pimcore automatically translates the label of every select. However, this means that sometimes necessary context is missing
from the translation-keys. To add more context, extend your constant class from the
[`TranslatableConstant`](https://github.com/passioneight/pimcore-utilities/blob/main/src/Constant/TranslatableConstant.php) class:

```php
<?php

namespace App\Constant;

use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\TranslatableConstant;

class Availability extends TranslatableConstant
{
    const IN_STOCK = "in-stock";
    const OUT_OF_STOCK = "out-of-stock";

    /**
     * @inheritdoc
     */
    public static function getTranslationKeyPrefix()
    {
        return "availability.";
    }
}
```

This will create the translation key `availability.in-stock`, instead of `in-stock`.

> Adding this context will help the client understand where the translation-key is used. Note that you should add such context sparsely.

### Additional Options Providers
- The `ImageThumbnailOptionsProvider` loads the available **image thumbnails** (defined via Pimcore) and converts them into options
to use with your `select`s.
- The `VideoThumbnailOptionsProvider` loads the available **video thumbnails** (defined via Pimcore) and converts them into options
to use with your `select`s.

### StoreProvider
Pimcore uses so-called stores for editables, such as the `pimcore_select`. Creating the options for the stores is - while
trivial - an unnecessary workload. This is where the `StoreProvider` comes in handy.

```php
<?php

namespace App\Controller\ContentController;

use App\Constant\Availability;
use App\OptionsProvider\CustomOptionsProvider;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\StoreProvider\StoreProvider;
use Pimcore\Controller\FrontendController;

class ContentController extends FrontendController
{
    /**
     * @var StoreProvider $storeProvider
     * @var CustomOptionsProvider $customOptionsProvider
     */
    public function defaultAction(StoreProvider $storeProvider, CustomOptionsProvider $customOptionsProvider)
    {
        $store = $storeProvider->getStoreForConstant(Availability::class);  // Convenience method to use constants directly
        $store = $storeProvider->getStoreForOptionsProvider($customOptionsProvider); // You can pass the context and field definition if needed
        
        // Omitted for brevity
    }
}
```

> The `StoreProvider` only converts the format of the options to reflect the format of a store. This implies that any
> translation-keys will also be used in the store. 

##### IconStoreProvider
In case a dropdown is supposed to contain icons, create your own `IconStoreProvider` and extend from the `AbstractIconStoreProvider` class.

```php
<?php

namespace App\Service\StoreProvider;

use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\StoreProvider\AbstractIconStoreProvider;

class IconStoreProvider extends AbstractIconStoreProvider
{
    /**
     * @inheritDoc
     */
    protected function getIconTemplate(): string
    {
        return 'Includes/icon.html.twig';
    }
}
```

```twig
{# Includes/icon.html.twig #}
<span class="icon icon-{{ icon }}"></span>
```

```php
<?php

namespace App\Controller\ContentController;

use App\Constant\Icon;
use App\OptionsProvider\CustomOptionsProvider;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\StoreProvider\StoreProvider;
use Pimcore\Controller\FrontendController;

class ContentController extends FrontendController
{
    /**
     * @var StoreProvider $storeProvider
     */
    public function defaultAction(IconStoreProvider $iconStoreProvider)
    {
        $store = $iconStoreProvider->getStoreForConstant(Icon::class);
        
        // Omitted for brevity
    }
}
```
### OptionsProvider Configuration
Pimcore provides additional fields which may be used within an `OptionsProvider`:
- `Default Value`,
- `Options Provider Data`,
- and others (see Pimcore's documentation).

##### Default Value
The `Default Value` lets one set a value that is pre-selected in the backend - this may improve usability within Pimcore
and is supported by the provided `OptionsProvider`s.

##### Options Provider Data
You can define `Options Provider Data` in the form of `JSON` to configure the options providers:

```json
{
  "constants-class": "App\\Constant\\NotTheFieldName",
  "static-options": "false"
}
```

> If the passed `JSON` is invalid, PHP's error message will be displayed. You may need to look up the meaning of the error message.

- The `constants-class` defines the class to use in the `ConstantsOptionsProvider` (e.g., when using a class from a bundle or when the
field name varies from the constant class' name). The namespace is optional and will fall back to `App\Constant` if not explicitly
provided.
- The `static-options` define whether the options are cached by Pimcore (i.e., cached options are only loaded once). The `JSON`
must only contain `"true"` or `"false"` if the value is provided.

> Sometimes it is necessary to configure the `OptionsProvider` programmatically. To do so, pass any aforementioned options
> as `context` to the `getOptions` method.