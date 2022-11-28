import React, {useContext} from 'react';

import { ConversationContext} from '../contexts/Contexts';

import API from '../API';

import AddPicture from '../icons/add_picture.svg';

const Input = (props) => {

    const {currentConversation} = useContext(ConversationContext);
    
    const handlePost = async() => {
        const msgInput = document.querySelector('#msg-input').value;
        if(msgInput && props.idContact){
            const response = await API.postMessage(currentConversation, msgInput);
            console.log(response);
            if(response.ok){
                document.querySelector('#msg-input').value = '';
            }
        }
    }

    return(
        <div className="h-[10%] bg-white p-[10px] flex items-center justify-between">
            <input id="msg-input" className="placeholder-gray-300 w-full outline-none text-violet-900" type="text" placeholder="Commencez à écrire ..." name="" />
            <div className="flex items-center gap-[10px]">
                <input className="hidden" type="file" id="file"/>
                <label htmlFor="file">
                    <img className="text-gray-300 h-[48px] cursor-pointer" src={AddPicture} alt="" />
                </label>
                <button onClick={() => {handlePost()}} className="py-[10px] px-[15px] border-2 border-violet-900 bg-violet-900 text-white hover:text-violet-900 hover:bg-white">Envoyer</button>
            </div>
        </div>
    );
};

export default Input;