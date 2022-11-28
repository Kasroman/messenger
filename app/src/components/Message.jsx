import React from 'react';

const Message = (props) => {

    const content = () => {
        if(props.message.type === 'text'){
            return <p className="bg-white py-[10px] px-[20px] rounded-tr-xl rounded-br-xl rounded-bl-xl">{props.message.content}</p>

        }else if(props.message.type === 'image'){
            return <img className="w-[50%]" src={'https://messenger/' + props.message.content} alt="" />
        }
    }

    return(
        <div className="m-[20px]">
            <div className="flex gap-[20px]">
                <div className="flex flex-col text-gray-600">
                    <img className="min-w-[50px] w-[50px] min-h-[50px] h-[50px] rounded-full object-cover" src={'https://messenger/' + props.message.img_contact} alt="" />
                </div>

                <div className="max-w-[80%] flex flex-col gap-[5px]">
                    {content()}
                    <span className="text-gray-600">{props.message.created_at}</span>
                </div>
            </div>
            
        </div>
    );
};

export default Message;