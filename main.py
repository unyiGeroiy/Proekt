import asyncio
from config import Bot_token
from aiogram import Bot, Dispatcher, types 
from aiogram.filters import CommandStart
from cat_fact import try_to_fact, try_to_images


bot = Bot(Bot_token)
dp = Dispatcher()


keyboard =types.ReplyKeyboardMarkup(
            keyboard=[
                    [types.KeyboardButton(text='Сгенерировать факт'),
                     types.KeyboardButton(text='Сгенерировать фото')
                     ]
                ],
                resize_keyboard=True
        )

@dp.message(CommandStart())
async def start_command(message: types.Message):
    await message.answer(
        'Привет! Этот бот выводит случайные факты кошек',
        reply_markup=keyboard
    )

@dp.message()
async def fact_answer(message: types.Message):
    if message.text == 'Сгенерировать факт':
        await message.answer(
            try_to_fact(), reply_markup=keyboard
        )
    else:
        await message.answer('Нажми на кнопку', reply_markup=keyboard)

@dp.message()
async def photo_answer(message: types.Message):
    if message.text == 'Сгенерировать фото':
        await bot.send_photo(message.chat.id, ur = try_to_images())
        await message.answer(reply_markup=keyboard)
    else:
        await message.answer(reply_markup=keyboard)

async def main():
    await dp.start_polling(bot)

if __name__ == '__main__':
    asyncio.run(main())
