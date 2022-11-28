import React, { useState } from 'react';
import { Navigate } from 'react-router-dom';

import { ConversationContext } from '../contexts/Contexts';

import Sidebar from '../components/Sidebar';
import Chat from '../components/Chat';

const Home = () => {

    const [currentConversation, setCurrentConversation] = useState(null);

    if(!localStorage.getItem('xsrfToken')){
        return <Navigate to='/login' />
    }

    return(
        <div className="bg-violet-600 h-[100vh] flex items-center justify-center">
            <div className="border-[1px] rounded-lg w-[66%] h-[80%] flex overflow-hidden shadow-2xl">
                <ConversationContext.Provider value={{ currentConversation, setCurrentConversation }}>
                    <Sidebar />
                    <Chat />
                </ConversationContext.Provider>
            </div>
        </div>
    );
};

export default Home;