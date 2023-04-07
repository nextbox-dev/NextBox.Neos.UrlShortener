# Url Shortener

This package provides an url shortening for Neos.

## Configuration

### Default

Add the following mixin to your document NodeType: `NextBox.Neos.UrlShortener:Identifier`

Create a new `Routes.yaml` with the following content:

```yaml
# Configuration/Routes.yaml
-
  name: 'Redirect for default'
  uriPattern: 'short/{shortIdentifier}' # replace `short` with your preferences
  defaults:
    '@package': 'NextBox.Neos.UrlShortener'
    '@controller': 'Redirect'
    '@action': 'redirectToPage'
    'shortType': 'default' # if you are using the default Mixin then do not change this line
  appendExceedingArguments: false
  httpMethods: ['GET']
```

### Extensibility

If you want to have different path names for url shortening than you can create your own configuration.

1. Create a Mixin or use an existing NodeType:
```yaml
# Configuration/NodeTypes.UrlIdentifier.yaml

'Foo.Bar:ProductIdentifier': # change with your name
  abstract: true
  properties:
    productId: # you can change the property name individually
      type: string
      ui:
        label: 'URL Short Identifier'
        reloadIfChanged: true
        inspector:
          group: 'document'
```

2. Create the setting
```yaml
# Configuration/Settings.yaml

NextBox:
  Neos:
    UrlShortener:
      shortTypes:
        'product': # change the name of the type
          nodeType: 'Foo.Bar:ProductIdentifier' # change the NodeType name to the new created Mixin or to the existing NodeType
          property: 'productId' # the property name of the identification - this field must be globally unique for the used Mixin
```

3. Create a route:

```yaml
# Configuration/Routes.yaml

-
  name: 'Redirect for product'
  uriPattern: 'product/{shortIdentifier}' # do not change `shortIdentifier`, just change the path for your preferences
  defaults:
    '@package': 'NextBox.Neos.UrlShortener'
    '@controller': 'Redirect'
    '@action': 'redirectToPage'
    'shortType': 'product' # change this to the name of the type from the settings
  appendExceedingArguments: false
  httpMethods: ['GET']
```
