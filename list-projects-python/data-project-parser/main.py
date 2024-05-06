import requests
from bs4 import BeautifulSoup


def get_html(url):
    r = requests.get(url)
    return r.content


def get_data(html):
    soup = BeautifulSoup(html, "lxml").prettify()
    h1 = soup.find('title')
    return h1


def main():
    url = "https://wordpress.com/"
    print(get_data(get_html(url)))


if __name__ == "__main__":
    main()
