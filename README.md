# Pimcore Options Providers
Pimcore supports the usage of _options providers_ to provide values in dropdowns. This bundle provides a default implementations
for options providers to avoid re-implementing them for every project.

###### Table of contents
- [Installation](/documentation/10_installation.md)
- [Usage](/documentation/20_usage.md)
    - [OptionsProviders](/documentation/20_usage.md#optionsproviders)
        - [ConstantOptionsProvider](/documentation/20_usage.md#constantoptionsprovider)
        - [Additional Options Providers](/documentation/20_usage.md#additional-options-providers)
    - [StoreProvider](/documentation/20_usage.md#storeprovider)
      - [IconStoreProvider](/documentation/20_usage.md#iconstoreprovider)
    - [OptionsProvider Configuration](/documentation/20_usage.md#optionsprovider-configuration)
        - [Default Value](/documentation/20_usage.md#default-value)
        - [Options Provider Data](/documentation/20_usage.md#options-provider-data)

# When should I use this bundle?
When providing options to choose from, both within Pimcore and on the website itself.

# Why should I use this bundle?
To decrease time to market, as implementing the various options providers can be quite the workload.