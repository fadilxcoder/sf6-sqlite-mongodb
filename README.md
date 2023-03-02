# Notes

- URL : `http://127.0.0.1:8881/`
- Profiler : `http://127.0.0.1:8881/_profiler`
- Permission issues on cache repo : `chown -R root:root var/cache/`
- `php.ini`

```
zend_extension=xdebug
[xdebug]
xdebug.mode=develop,debug
xdebug.client_host=host.docker.internal
xdebug.start_with_request=yes
```

- `docker-compose.yml`

```
    extra_hosts:
      - "host.docker.internal:host-gateway"
```

- `launch.json`

```
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Xdebug Symfony MVP",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html/": "${workspaceFolder}/src/"
            }
        }
    ]
}
```

<img src="./_docs/xdebug-vscode.png" alt=""/>
