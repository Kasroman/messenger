class API {

    static async register(mail, pseudo, password) {

        const headers = new Headers();
        headers.append('Origin', 'https://localhost:3000');

        const requestOptions = {
            method: 'POST',
            mode: 'cors',
            headers: headers,
            body: JSON.stringify({
                "mail": mail,
                "password": password,
                "pseudo": pseudo
            })
        };

        const response = await fetch("https://messenger/Api/register", requestOptions);
        return response;
    }

    static async login(mail, password) {

        const headers = new Headers();
        headers.append('Origin', 'https://localhost:3000');

        const xsrfToken = localStorage.getItem('xsrfToken');
        if(xsrfToken){
            headers.append("X-XSRF-TOKEN", xsrfToken);
        }

        const requestOptions = {
            method: 'POST',
            mode: 'cors',
            headers: headers,
            credentials: 'include',
            body: JSON.stringify({
                "mail": mail,
                "password": password
            })
        };

        const response = await fetch("https://messenger/Api/login", requestOptions);
        return response;
    }

    static async logout(){

        const headers = new Headers();
        headers.append('Origin', 'https://localhost:3000');

        const xsrfToken = localStorage.getItem('xsrfToken');
        if(xsrfToken){
            headers.append("X-XSRF-TOKEN", xsrfToken);
        }

        const requestOptions = {
            method: 'GET',
            mode: 'cors',
            headers: headers,
            credentials: 'include'
        };

        const response = await fetch("https://messenger/Api/logout", requestOptions);
        return response;
    }

    static async forgotPassword(mail){
        const headers = new Headers();
        headers.append('Origin', 'https://localhost:3000');

        const requestOptions = {
            method: 'POST',
            mode: 'cors',
            headers: headers,
            body: JSON.stringify({
                "mail": mail
            })
        };

        const response = await fetch("https://messenger/Api/resetPassword", requestOptions);
        return response;
    }

    static async resetPassword(token, password){

        const headers = new Headers();
        headers.append('Origin', 'https://localhost:3000');

        const requestOptions = {
            method: 'PUT',
            mode: 'cors',
            headers: headers,
            credentials: 'include',
            body: JSON.stringify({
                "password": password
            })
        };

        const response = await fetch(`https://messenger/Api/resetPassword/${token}`, requestOptions);
        return response;
    }

    static async searchUsers(pseudo){
        const headers = new Headers();
        headers.append('Origin', 'https://localhost:3000');

        const xsrfToken = localStorage.getItem('xsrfToken');
        if(xsrfToken){
            headers.append("X-XSRF-TOKEN", xsrfToken);
        }

        const requestOptions = {
            method: 'GET',
            mode: 'cors',
            headers: headers,
            credentials: 'include'
        };

        const response = await fetch(`https://messenger/Api/searchUsers/${pseudo}`, requestOptions);
        return response;
    }
}

export default API;