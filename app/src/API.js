class API {

    static jsonToArray(json, withKey = false){
        let data = [];
        for (const [key, value] of Object.entries(json)){
            if(withKey){
                data[key] = value;
            }else{
                data.push(value);
            }
        }
        return data;
    }

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

    static async getConversations(){
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

        const response = await fetch(`https://messenger/Api/getConversations`, requestOptions);
        return response;
    }

    static async getMessages(idContact){
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

        const response = await fetch(`https://messenger/Api/getMessages/${idContact}`, requestOptions);
        return response;
    }

    static async postMessage(idContact, content){

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
                "content": content,
            })
        };

        const response = await fetch(`https://messenger/Api/postMessage/${idContact}`, requestOptions);
        return response;
    }

    static async postImage(idContact, image){
        const headers = new Headers();
        headers.append('Origin', 'https://localhost:3000');

        const xsrfToken = localStorage.getItem('xsrfToken');

        if(xsrfToken){
            headers.append("X-XSRF-TOKEN", xsrfToken);
        }

        const data = new FormData();
        data.append('image', image);

        const requestOptions = {
            method: 'POST',
            mode: 'cors',
            headers: headers,
            credentials: 'include',
            body: data
        };

        const response = await fetch(`https://messenger/Api/postMessage/${idContact}`, requestOptions);
        return response;
    }
}

export default API;