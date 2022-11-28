import React from 'react';

import API from '../API';

const Navbar = () => {

    const logout = async() => {

        const response = await API.logout();
        console.log(response);
        if(!response.ok){
            const json = await response.json();
            console.log(json);
        }else{
            localStorage.removeItem('xsrfToken');
            window.location.reload(false);
        } 
    }

    return(
        <div className="flex items-center bg-gray-900 h-[50px] p-[10px] justify-between text-white">
            <span className="font-bold">Messenger</span>
            <div className="flex gap-[10px] items-center">
                <img className="bg-white h-[24px] w-[24px] rounded-full object-cover" src="https://messenger/images/profile/default_pp.png" alt="" />
                <span>Roman</span>
                <button onClick={logout} className="bg-violet-900 text-xs p-[4px]">Deconnexion</button>
            </div>
        </div>
    );
};

export default Navbar;