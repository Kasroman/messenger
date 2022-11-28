import React, { useState, useEffect } from 'react';

import Message from './Message';

import API from '../API';

const Messages = (props) => {

    const [messages, setMessages] = useState(null);

    useEffect(() => {
        const refresh = setInterval(async() => {
            if(props.idContact){
                const response = await API.getMessages(props.idContact);

                if(!response.ok){
                    setMessages(null);
                }else{
                    const json = await response.json();
                    setMessages(API.jsonToArray(json));
                }
            }else{
                setMessages(null);
            }
        }, 1000);

        return () => {
            clearInterval(refresh);
        };

    },[props.idContact]);

    return(
        <div id="chat" className="bg-violet-300 p-[10px] h-[90%] overflow-y-scroll flex flex-col-reverse">
            {messages && messages.map((message) => {
                return <Message key={message.id} message={message} />
            })}
        </div>
    );
};

export default Messages;