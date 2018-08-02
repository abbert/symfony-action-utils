# symfony-action-utils
Implements all the methods defined in Symfony's ControllerTrait. Can be injected in controllers / actions.

sample service definition
------------

```yaml
Skrepr\ActionUtils\ActionUtils:
    arguments: ['@twig', '@form.factory', '@session', '@router', '@doctrine', '@security.token_storage']
 ```
