import React, { useState } from 'react';
import { Navigate, Link } from 'react-router-dom';

import API from '../API';

const Register = () => {

    const [err, setErr] = useState(false);

    const handleSubmit = async(e) => {
        e.preventDefault();

        if(e.target[0].value === '' || e.target[1].value === '' || e.target[2].value === ''){
            return
        }

        const pseudo = e.target[0].value;
        const mail = e.target[1].value;
        const password = e.target[2].value;

        try{
            const response = await API.register(mail, pseudo, password);
            if(!response.ok){
                const json = await response.json();
                throw json.message;
            }else{
                window.location.replace("http://localhost:3000/login");
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
                <span className="text-violet-600 text-lg">Inscription</span>
                <form onSubmit={handleSubmit} className="flex flex-col gap-[15px]">
                    <input className="p-[15px] border-b-2 border-b-violet-300 outline-none placeholder-gray-300" type="text" placeholder="pseudo"/>
                    <input className="p-[15px] border-b-2 border-b-violet-300 outline-none placeholder-gray-300" type="email" placeholder="mail"/>
                    <input className="p-[15px] border-b-2 border-b-violet-300 outline-none placeholder-gray-300" type="password" placeholder="password"/>
                    <button className="bg-violet-600 text-white p-[10px] border-2 border-white font-bold cursor-pointer hover:bg-white hover:text-violet-600 hover:border-violet-600">M'inscrire</button>
                    {err && <span>{err}</span>}
                </form>
                <Link to="/login" className="text-violet-300 cursor-pointer">Déjà inscrit ? Connexion</Link>
            </div>
        </div>
    );
};

export default Register;