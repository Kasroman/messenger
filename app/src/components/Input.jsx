import React from 'react';

import AddPicture from '../icons/add_picture.svg';

const Input = () => {
    return(
        <div className="h-[10%] bg-white p-[10px] flex items-center justify-between">
            <input className="placeholder-gray-300 w-full outline-none text-violet-900" type="text" placeholder="Commencez à écrire ..." name="" id="" />
            <div className="flex items-center gap-[10px]">
                <input className="hidden" type="file" id="file"/>
                <label htmlFor="file">
                    <img className="text-gray-300 h-[48px] cursor-pointer" src={AddPicture} alt="" />
                </label>
                <button className="py-[10px] px-[15px] border-2 border-violet-900 bg-violet-900 text-white hover:text-violet-900 hover:bg-white">Envoyer</button>
            </div>
        </div>
    );
};

export default Input;