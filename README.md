Friends-On-App
==============

TODO

Parameterized Config
====================

Instead of exposing your app credentials to the world,
using the Parameterized class will load it from a config file.

The config-file should contain only key-value pairs, and must
be named `config.params`

```
	key: value
```

Oauth
=====

The OAUTH-2.0 spec is now available (to some, untested extent).

Currently, only Facebook has a concrete OAUTH provider.

To set parameters for oauth providers, like the client-secret
and client-id, add them to the config like so:

```
	PROVIDER_APP_ID: theid
	PROVIDER_APP_SECRET: thesecret
```

Replace `PROVIDER` with the provider type (eg: FACEBOOK, GOOGLE etc).
