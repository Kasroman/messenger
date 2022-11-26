import React from 'react';

const Chats = () => {
    return(
        <div>
            <div className="p-[10px] flex items-center gap-[10px] text-white cursor-pointer hover:bg-gray-900">
                <img className="w-[50px] h-[50px] rounded-full object-cover" src="http://messenger/images/profile/default_pp.png" alt="" />
                <div>
                    <span className="text-lg font-bold">Jane</span>
                    <p className="text-sm">Hello !</p>
                </div>
            </div>

            <div className="p-[10px] flex items-center gap-[10px] text-white cursor-pointer hover:bg-gray-900">
                <img className="w-[50px] h-[50px] rounded-full object-cover" src="http://messenger/images/profile/default_pp.png" alt="" />
                <div>
                    <span className="text-lg font-bold">Jane</span>
                    <p className="text-sm">Hello !</p>
                </div>
            </div>

            <div className="p-[10px] flex items-center gap-[10px] text-white cursor-pointer hover:bg-gray-900">
                <img className="w-[50px] h-[50px] rounded-full object-cover" src="http://messenger/images/profile/default_pp.png" alt="" />
                <div>
                    <span className="text-lg font-bold">Jane</span>
                    <p className="text-sm">Hello !</p>
                </div>
            </div>
        </div>
    );
};

export default Chats;