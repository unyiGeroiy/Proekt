import requests

def try_to_fact():
    try:
        r = requests.get('https://catfact.ninja/fact')
        if r.status_code == 200:
            r_dict = r.json()
            return r_dict['fact']
        else:
            return f'Ошибка сервера: {r.status_code}'
    except requests.exceptions.RequestException as e:
        return f'Ошибка сети: {e}'

def try_to_images():
    try:
        r = requests.get('https://api.thecatapi.com/v1/images/search')
        if r.status_code == 200:
            data = r.json()
            return data[0]['url']
        else:
            print('Ошибка сервера')
    except requests.exceptions.RequestException as e:
        print(e)





