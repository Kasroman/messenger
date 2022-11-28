import React, { useState, useContext } from 'react';

import { ConversationContext, NewchatContext } from '../contexts/Contexts';

import API from '../API';

const Search = () => {

    const {setCurrentConversation} = useContext(ConversationContext);
    const [, setNewchat] = useContext(NewchatContext);

    const [users, setUsers] = useState(null);

    const handleChange = async(input) => {
        if(input && !input.includes(' ')){
            const response = await API.searchUsers(input);
            if(!response.ok){
                setUsers(null);
            }else{
                const json = await response.json();
                const array = API.jsonToArray(json);
                setUsers(array);
            }
        }else{
            setUsers(null);
        }
    }

    return(
        <div className="border-[1px] border-gray-300">
            <div className="p-[10px]">
                <input className="bg-transparent text-white outline-none" onChange={(e) => {handleChange(e.target.value)}} type="text" name="" id="" placeholder="find a user" />
            </div>
            {users && users.map(user => {
                return(
                    <div key={user.id} onClick={() => {
                            setUsers(null);
                            setNewchat(user);
                            setCurrentConversation(user.id);
                        }} className="p-[10px] flex items-center gap-[10px] text-white cursor-pointer hover:bg-gray-900">
                        <img className="w-[50px] h-[50px] rounded-full object-cover" src={'https://messenger/' + user.img} alt="" />   
                        <div>
                            <span>{user.pseudo}</span>
                        </div>
                    </div>
                );
            })}
        </div>
    );
};

export default Search;

