# Customization
You can decorate any services (i.e., `OptionsProvider`s and `StoreProvider`s), as described in Symfony's documentation.
By doing so, you can customize their implementation.

If your use-case cannot be handled by the provided classes, consider extending from the `AbstractOptionsProvider` class
to create the `OptionsProvider` that suits you best.