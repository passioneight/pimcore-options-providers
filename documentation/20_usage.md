# Usage
This bundle aims to reduce the implementation effort for `OptionsProvider`s. The provided classes are explained below.

### OptionsProviders
Various `OptionsProvider`s are, which work closely with Pimcore's class definitions.

###### ConstantOptionsProvider
The `ConstantOptionsProvider` provides an easy-to-use way to provide options (e.g., for a `select` field), due to its generic
approach.

First, create your constant class - for example, the `Availability` class:
```php
<?php

namespace AppBundle\Constant;

use Passioneight\Bundle\PhpUtilitiesBundle\Constant\Constant;

class Availablility extends Constant
{
    const IN_STOCK = "in-stock";
    const OUT_OF_STOCK = "out-of-stock";
}
```

Next, you'll need to open the class definitions and edit your `Select`s, in that, the
**Options Provider Class or Service name** field holds the value
`@Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\OptionsProvider\ConstantOptionsProvider`.

> The `@` is important, as we want to use the `OptionsProvider` as service.

Assume that a `Product` class definition exists and the `OptionsProvider` was set as described before - for the field
`availability`.

The `ConstantOptionsProvider` will automatically look for the class `AppBundle\Constant\Availability`, as this was inferred
from the field name. If a class was found, the values of the class are used - provided that a `getAll` method was implemented.

> Using the constant-class of our [Php Utilities Bundle](https://github.com/passioneight/php-utilities), the method will be available automatically.

To increase usability for your clients, you'll want to localize the options. That is, you want to translate the label, so
that the client sees the options in their preferred language. You can achieve this, by extending your constant class
from the `TranslatableConstant` instead of the `Constant` class, and implementing the `getTranslationKeyPrefix` method:

```php
<?php

namespace AppBundle\Constant;

use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\TranslatableConstant;

class Availablility extends TranslatableConstant
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

This will create the translation key `availability.in-stock`, which you can now set to any value using the `Shared Translations`.

###### ImageThumbnailOptionsProvider
The `ImageThumbnailOptionsProvider` loads the available **image thumbnails** (defined via Pimcore) and converts them into options
to use with your `select`s.

> Note that the options cannot be translated at this point. 

###### VideoThumbnailOptionsProvider
The `VideoThumbnailOptionsProvider` loads the available **video thumbnails** (defined via Pimcore) and converts them into options
to use with your `select`s.

> Note that the options cannot be translated at this point. 

### OptionsProvider Configuration
Pimcore provides additional fields which may be used within an `OptionsProvider`:
- **Default Value**,
- **Options Provider Data**,
- and others (see Pimcore's documentation).

###### Default Value
The **Default Value** lets one set a value that is pre-selected in the backend - this may improve usability within Pimcore
and is supported by the provided `OptionsProvider`s.

###### Options Provider Data
You can define **Options Provider Data**, which comes in handy when different behaviour of the `OptionsProvider`s is needed.
For example, you may set the following options as `JSON`:

```json
{
  "constants-class": "AppBundle\\Constant\\TournamentType",
  "static-options": "false"
}
```

> If the passed `JSON` is invalid, Php's error message will be displayed. You may need to look up the meaning of the error message.

The `constants-class` defines the class to use in the `ConstantsOptionsProvider` (e.g., when using a class from a bundle).

> Note that you do not need the FQCN - however, if you do not provide the namespace, the `AppBundle\Constant` namespace is assumed.

The `static-options` lets you change whether the `OptionsProvider` is supposed to use static/dynamic options.

> Note that you can only pass `"true"` or `"false"`.

> Sometimes it is necessary to configure the `OptionsProvider` programmatically. To do so, pass any aforementioned options
> as context to the `getOptions` method.

### StoreProvider
The `StoreProvider` provides a simple way to create a _store_, which is required by some **editables**. Thus, it's
a convenient way to provide options for your content-elements (e.g., when using a `pimcore_select`).

As the `StoreOptionsProvider` is defined as service, you can inject it into your controller and create a store
from an `OptionsProvider`:

```php
<?php

namespace AppBundle\Controller\ContentController;

use AppBundle\Constant\Availability;
use AppBundle\OptionsProvider\CustomOptionsProvider;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\StoreProvider\StoreProvider;
use Pimcore\Controller\FrontendController;

class ContentController extends FrontendController
{
    /**
     * @var StoreProvider $storeProvider
     * @var CustomOptionsProvider $customOptionsProvider
     */
    public function defaultAction(StoreProvider $storeProvider, CustomOptionsProvider $customOptionsProvider)
    {
        $this->view->store = $storeProvider->getStoreForConstant(Availability::class);  // Convenience method to use constants directly
        $this->view->store = $storeProvider->getStoreForOptionsProvider($customOptionsProvider); // You can pass the context and field definition if needed
    }
}
```

> The `StoreOptionsProvider` will display the options in the same way as the passed `OptionsProvider`. Thus, when using a
> `TranslatableConstant`, the corresponding translation keys will be used.

###### IconStoreProvider
More often than not, it is necessary to provide a `pimcore_select` containing icons. To directly display the icons, HTML
can be rendered as select-options. However, doing this manually for every `pimcore_select` is quite cumbersome. Thus,
the `AbstractIconStoreProvider` was added.

Create a service (e.g., `IconStoreProvider`) and extend from the `AbstractIconStoreProvider` class. Next, return the path
to the `Twig`-template in the `getIconTemplate` method:

```php
<?php

namespace AppBundle\Service\StoreProvider;

use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\StoreProvider\AbstractIconStoreProvider;

class IconStoreProvider extends AbstractIconStoreProvider
{
    /**
     * @inheritDoc
     */
    protected function getIconTemplate(): string
    {
        return ':Includes:icon.html.twig';
    }
}
```

Now, implement the template itself, similar to:

```twig
<span class="icon icon-{{ icon }}"></span>
```

Finally, create your store as mentioned before: `$this->view->store = $storeProvider->getStoreForConstant(Icon::class);`