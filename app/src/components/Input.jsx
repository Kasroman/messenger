import React, {useContext, useState} from 'react';

import { ConversationContext} from '../contexts/Contexts';

import API from '../API';

import AddPicture from '../icons/add_picture.svg';

const Input = (props) => {

    const {currentConversation} = useContext(ConversationContext);

    const [errImgUpload, setErrImgUpload] = useState(null);
    
    const handlePost = async() => {

        if(errImgUpload){
            setErrImgUpload(null);
        }

        const msgInput = document.querySelector('#msg-input');
        const imgInput = document.querySelector('#img-input');

        if(imgInput.files[0] && props.idContact){
            const response = await API.postImage(currentConversation, imgInput.files[0]);
            if(response.ok){
                imgInput.value = '';
                document.querySelector('#preview').src = '';
            }else{
                imgInput.value = '';
                const [array] = (API.jsonToArray(await response.json())).map(message => {return JSON.parse(message)});
                setErrImgUpload(array);
            }
        }

        if(msgInput.value && props.idContact){
            const response = await API.postMessage(currentConversation, msgInput.value);
            if(response.ok){
                msgInput.value = '';
            }
        }
    }

    const handlePreview = (e) => {
        const preview = document.querySelector('#preview');
        if(e.target.files[0]){
            preview.src = URL.createObjectURL(e.target.files[0]);
            preview.addEventListener('load', () => {
                URL.revokeObjectURL(preview.src);
            });

        }else{
            preview.src = '';
        }
    }

    return(
        <div className="h-[10%] bg-white p-[10px] flex items-center justify-between">
            <div className="flex flex-col">
                {errImgUpload && errImgUpload.map((err) => {
                    return(<p key={Math.floor(Math.random() * 100)} className="text-red-600 text-[12px]">{err}</p>)
                })}
                <input id="msg-input" className="placeholder-gray-400 w-full outline-none text-violet-900" type="text" placeholder="Commencez à écrire ..." name="" />
            </div>
            <div className="flex items-center gap-[10px]">
                <img id="preview" className="h-[48px] min-w-[36px]" src="" alt="" />
                <input onChange={(e) => {handlePreview(e)}} className="hidden" type="file" id="img-input" accept="image/png, image/jpg, image/jpeg"/>
                <label htmlFor="img-input">
                    <img className="text-gray-300 h-[48px] min-w-[36px] cursor-pointer" src={AddPicture} alt="" />
                </label>
                <button onClick={() => {handlePost()}} className="py-[10px] px-[15px] border-2 border-violet-900 bg-violet-900 text-white hover:text-violet-900 hover:bg-white">Envoyer</button>
            </div>
        </div>
    );
};

export default Input;