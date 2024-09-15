cooldownTime=86400
username="username"
password="password"

import flask
import claptcha
import random
import time
import requests

acitveAnswers={}
coolDowns={"u":{}, "ip":{}}

def randomString():
    rndLetters = (random.choice("qwertyuiopasdfghjklzxcvbnm1234567890") for _ in range(random.randint(4, 6)))
    return "".join(rndLetters)

def makeCaptcha():
    c = claptcha.Claptcha(randomString(), "Consolas.ttf")
    text, image= c.bytes
    return text, image
    

app = flask.Flask(__name__)

@app.route('/captcha.png', methods=['GET'])
def captcha():
    text, img = makeCaptcha()
    print(text)
    acitveAnswers[flask.request.remote_addr]=text
    return img

def send(user):
    amm = round(random.random()*3,2)
    url="https://server.duinocoin.com/transaction/?username=" + username + "&password=" + password + "&recipient=" + user + "&amount=" + str(amm) + "&memo=KatFaucet"
    requests.get(url)
    return amm

@app.route('/', methods=['POST', 'GET'])
def index():
    if flask.request.method == 'POST':
        data = flask.request.form
        user = data.get('username')
        answer = data.get('captcha')
        if flask.request.remote_addr in acitveAnswers.keys():
            if coolDowns["u"].get(user, 0) < time.time() and coolDowns["ip"].get(flask.request.remote_addr, 0) < time.time():
                if acitveAnswers[flask.request.remote_addr]==answer:
                    coolDowns["u"][user]=time.time()+cooldownTime
                    coolDowns["ip"][flask.request.remote_addr]=time.time()+cooldownTime
                    ducos = send(user)
                    acitveAnswers.pop(flask.request.remote_addr)
                    return "<h1>Success! Sent "+str(ducos)+"ducos to "+user+" </h1>"
                else:
                    return "<h1>Captcha incorrect</h1>"
            else:
                return "<h1>You are on cooldown, please wait for "+str(max(int(coolDowns["u"][user]-time.time()),int(coolDowns["ip"][flask.request.remote_addr]-time.time())))+" seconds</h1>"
        else:
            return "<h1>Please wait for captcha to load</h1>"
    elif flask.request.method == 'GET':
        with open("index.html", "r") as f:
            return f.read()

app.run(port=8080)