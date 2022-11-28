import React, { useState, useEffect, useContext } from 'react';

import { ConversationContext, NewchatContext } from '../contexts/Contexts';

import API from '../API';

const Chats = () => {

    const {currentConversation, setCurrentConversation} = useContext(ConversationContext);
    const [newChat, setNewChat] = useContext(NewchatContext);

    const [conversations, setConversations] = useState(null);

    useEffect(() => {
        setInterval(async() => {
            const response = await API.getConversations();

            if(!response.ok){
                setConversations(null);
            }else{
                const json = await response.json();
                setConversations(API.jsonToArray(json));
            }
        }, 1000);
    },[]);

    useEffect(() => {
        if(conversations && newChat){
            conversations.forEach(conversation => {
                if(newChat && newChat.id === conversation.id_contact){
                    setNewChat(null);
                }
            })
        }
    }, [conversations]);

    return(
        <div className="overflow-y-scroll overflow-x-hidden">

            {newChat && <div className="p-[10px] bg-violet-600 flex items-center gap-[10px] text-white cursor-pointer hover:bg-gray-900">
                <img className="w-[50px] h-[50px] rounded-full object-cover" src={'https://messenger/' + newChat.img} alt="" />
                <div>
                    <span className="text-lg font-bold">{newChat.pseudo}</span>
                </div>
            </div>}

            {conversations && conversations.map((conversation) => {

                const bgColor = () => {
                    if(currentConversation === conversation.id_contact){
                        return 'bg-violet-600';
                    }
                    return 'bg-gray-700';
                }

                return(
                    <div key={conversation.id_contact} onClick={() => {
                        setCurrentConversation(conversation.id_contact);
                        setNewChat(null);
                    }} 
                    className={'p-[10px] flex items-center gap-[10px] text-white cursor-pointer hover:bg-gray-900 '
                    + bgColor() }>
                        <img className="w-[50px] h-[50px] rounded-full object-cover" src={'https://messenger/' + conversation.img_contact} alt="" />
                        <div>
                            <span className="text-lg font-bold">{conversation.pseudo_contact}</span>
                            <p className="text-sm">{conversation.content}</p>
                        </div>
                    </div>
                );
            })}
        </div>
    );
};

export default Chats;