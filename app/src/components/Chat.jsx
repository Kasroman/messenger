import React from 'react';

import Messages from './Messages';
import Input from './Input';

const Chat = () => {
    return(
        <div className="w-2/3 h-full">
            <Messages />
            <Input />
        </div>
    );
};

export default Chat;