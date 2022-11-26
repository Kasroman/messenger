import React, { useState, useEffect } from 'react';

import API from '../API';

const Search = () => {

    const [users, setUsers] = useState(null);

    const handleChange = async(input) => {
        const response = await API.searchUsers(input);
        
        if(!response.ok){
            console.log(response);
            // setUsers(null);
        }else{
            console.log(response);
            // setUsers(response.json());
        }
        // console.log(users);
    }

    return(
        <div className="border-[1px] border-gray-300">
            <div className="p-[10px]">
                <input className="bg-transparent text-white outline-none" onChange={(e) => {handleChange(e.target.value)}} type="text" name="" id="" placeholder="find a user" />
            </div>
            {/* {users && users.map((user) => {
                <div className="p-[10px] flex items-center gap-[10px] text-white cursor-pointer hover:bg-gray-900">
                    <img className="w-[50px] h-[50px] rounded-full object-cover" src="http://messenger/images/profile/default_pp.png" alt="" />   
                    <div>
                        <span>Jane</span>
                    </div>
                </div>
            })} */}
        </div>
    );
};

export default Search;