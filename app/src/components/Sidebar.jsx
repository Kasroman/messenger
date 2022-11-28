import React, { useState } from 'react';

import { NewchatContext } from '../contexts/Contexts';

import Navbar from './Navbar';
import Search from './Search';
import Chats from './Chats';

const Sidebar = () => {
    
    const [newChat, setNewChat] = useState(null);

    return(
        <div className="w-1/3 bg-gray-700">
            <NewchatContext.Provider value={[ newChat, setNewChat ]}>
                <Navbar />
                <Search />
                <Chats />
            </NewchatContext.Provider>
        </div>
    );
};

export default Sidebar;