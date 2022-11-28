import React, { useContext } from 'react';

import { ConversationContext } from '../contexts/Contexts';

import Messages from './Messages';
import Input from './Input';

const Chat = () => {

    const {currentConversation} = useContext(ConversationContext);

    return(
        <div className="w-2/3 h-full">
            <Messages idContact={currentConversation} />
            <Input idContact={currentConversation} />
        </div>
    );
};

export default Chat;