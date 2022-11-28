import React, { useState } from 'react';
import { Navigate, Link } from 'react-router-dom';

import API from '../API';

const Login = () => {

    const [err, setErr] = useState(false);

    const handleSubmit = async(e) => {
        e.preventDefault();

        if(e.target[0].value === '' || e.target[1].value === ''){
            setErr('Champs vides.');
            return
        }

        const mail = e.target[0].value;
        const password = e.target[1].value;

        try{
            const response = await API.login(mail, password);
            if(!response.ok){
                const json = await response.json();
                throw json.message;
            }else{
                const json = await response.json();
                localStorage.setItem('xsrfToken', json.xsrfToken); 
                window.location.reload(false);
            }
        }catch(err){
            setErr(err);
        } 
    }

    if(localStorage.getItem('xsrfToken')){
        return <Navigate to='/' />
    }

    return(
        <div className="bg-violet-300 h-[100vh] flex items-center justify-center">
            <div className="bg-white py-[20px] px-[60px] rounded-lg flex flex-col gap-[10px] items-center">
                <span className="text-violet-600 font-bold text-2xl">Messenger</span>
                <span className="text-violet-600 text-lg">Connexion</span>
                <form onSubmit={handleSubmit} className="flex flex-col gap-[15px]">
                    <input className="p-[15px] border-b-2 border-b-violet-300 outline-none placeholder-gray-300" type="email" placeholder="mail"/>
                    <input className="p-[15px] border-b-2 border-b-violet-300 outline-none placeholder-gray-300" type="password" placeholder="password"/>
                    <Link to="/reset" className="text-violet-300 cursor-pointer">Mot de passe oublié ?</Link>
                    <button className="bg-violet-600 text-white p-[10px] border-2 border-white font-bold cursor-pointer hover:bg-white hover:text-violet-600 hover:border-violet-600">Me connecter</button>
                    {err && <span className="text-red-600 text-center text-sm">{err}</span>}
                </form>
                <Link to="/register" className="text-violet-300 cursor-pointer">Pas de compte ? Inscription</Link>
            </div>
        </div>
    );
};

export default Login;