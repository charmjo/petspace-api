# Web Requests
1. Please send a preflight request before POSTing a request to the api.

See postman code below:
```js
    pm.sendRequest({
        url:'http://localhost:8000/sanctum/csrf-cookie',
        method: 'GET'
    },
    function (error, response, { cookies }) {
            if (!error) {
                pm.environment.set('xsrf-cookie', cookies.get('XSRF-TOKEN'))
            }
            console.log(error);
        }
    )
```

2. Send cookie from sanctum on the headers when making the post request.

