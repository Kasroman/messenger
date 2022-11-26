import React from 'react';

const Message = () => {
    return(
        <div className="flex gap-[20px]">
            <div className="flex flex-col text-gray-600">
                <img className="min-w-[50px] w-[50px] min-h-[50px] h-[50px] rounded-full object-cover" src="http://messenger/images/profile/default_pp.png" alt="" />
                <span>just now</span>
            </div>

            <div className="max-w-[80%] flex flex-col gap-[10px]">
                <p className="bg-white py-[10px] px-[20px] rounded-tr-xl rounded-br-xl rounded-bl-xl">Hello !</p>
                {/* <img className="w-[50%]" src="http://messenger/images/profile/default_pp.png" alt="" /> */}
            </div>
        </div>
    );
};

export default Message;