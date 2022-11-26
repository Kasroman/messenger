import React, { useState } from 'react';
import { Navigate, Link, useParams } from 'react-router-dom';

import API from '../API';

const Reset = () => {

    const [msg, setMsg] = useState(false);

    const { token } = useParams();

    const handleSubmit = async(e) => {
        e.preventDefault();

        if(e.target[0].value === ''){
            setMsg('Champs vide.');
            return;
        }

        const mail = e.target[0].value;

        try{
            const response = await API.forgotPassword(mail);
            console.log(response);
            if(!response.ok){
                const json = await response.json();
                throw json.message;
            }else{
                setMsg('Un mail vient de vous être envoyé.');
            }
        }catch(msg){
            setMsg(msg);
        } 
    }

    const handleSubmit2 = async(e) => {
        e.preventDefault();

        if(e.target[0].value === ''){
            setMsg('Champs vide.');
            return;
        }

        const password = e.target[0].value;

        try{
            const response = await API.resetPassword(token, password);
            if(!response.ok){
                const json = await response.json();
                throw json.message;
            }else{
                setMsg(false);
                window.location.href = "http://localhost:3000/login";
            }
        }catch(msg){
            setMsg(msg);
        } 
    }

    if(localStorage.getItem('xsrfToken')){
        return <Navigate to='/' />;
    }

    if(token){
        return(
        <div className="bg-violet-300 h-[100vh] flex items-center justify-center">
            <div className="bg-white py-[20px] px-[60px] rounded-lg flex flex-col gap-[10px] items-center">
                <span className="text-violet-600 font-bold text-2xl">Messenger</span>
                <span className="text-violet-600 text-lg">Nouveau mot de passe</span>
                <form onSubmit={handleSubmit2} className="flex flex-col gap-[15px]">
                    <input className="p-[15px] border-b-2 border-b-violet-300 outline-none placeholder-gray-300" type="password" placeholder="nouveau mot de passe"/>
                    <button className="bg-violet-600 text-white p-[10px] border-2 border-white font-bold cursor-pointer hover:bg-white hover:text-violet-600 hover:border-violet-600">Confirmer</button>
                    {msg && <span className="text-red-600 text-center text-sm">{msg}</span>}
                </form>
            </div>
        </div>);    
    }

    return(
        <div className="bg-violet-300 h-[100vh] flex items-center justify-center">
            <div className="bg-white py-[20px] px-[60px] rounded-lg flex flex-col gap-[10px] items-center">
                <span className="text-violet-600 font-bold text-2xl">Messenger</span>
                <span className="text-violet-600 text-lg">Mot de passe oublié</span>
                <form onSubmit={handleSubmit} className="flex flex-col gap-[15px]">
                    <input className="p-[15px] border-b-2 border-b-violet-300 outline-none placeholder-gray-300" type="email" placeholder="mail"/>
                    <Link to="/login" className="text-violet-300 cursor-pointer">Se connecter ?</Link>
                    <button className="bg-violet-600 text-white p-[10px] border-2 border-white font-bold cursor-pointer hover:bg-white hover:text-violet-600 hover:border-violet-600">Confirmer</button>
                    {msg && <span className="text-red-600 text-center text-sm">{msg}</span>}
                </form>
            </div>
        </div>
    );
};

export default Reset;