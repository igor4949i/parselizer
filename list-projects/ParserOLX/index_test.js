const Nightmare = require("nightmare");
import mongoose from "mongoose";
const rp = require("request-promise");
import AddSchema from "./addModel";
import { userAgents } from "./userAgents";

const getRandomNumber = (min, max) => {
  return Math.ceil(Math.random() * (max - min) + min);
};


mongoose.connect(
  `mongodb://localhost:27017/likimap`,
  { useCreateIndex: true, useNewUrlParser: true },
  err => {
    if (err) {
      throw err;
    }
  }
);

const proxyUser = {
  login: "gerraweb1",
  password: "gK0DqH82",
  port: "2831"
};

const proxies = [
  {
    ip: "195.123.191.87",
    useragent:
      "Mozilla/5.0 (X11; CrOS x86_64 8172.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.64 Safari/537.36"
  },
  {
    ip: "195.123.193.70",
    useragent:
      "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246"
  },
  {
    ip: "195.123.197.206",
    useragent:
      "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9"
  },
  {
    ip: "195.123.198.39",
    useragent:
      "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1"
  },
  {
    ip: "212.86.111.83",
    useragent:
      "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36"
  }
];

process.env["NODE_TLS_REJECT_UNAUTHORIZED"] = "0";

let currentProxyUsed = 0;
let requestWithCurrentProxy = 0;

const startURL = process.argv[2];
const countNeeded = process.argv[3];

let lastUsedUserAgent = 0;

const getCategoryList = async (
  url,
  pagesCount = 1,
  currentPage = 1,
  list = []
) => {
  const nightmare = Nightmare();
  nightmare.useragent(
    "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36 OPR/38.0.2220.41"
  );

  if (currentPage > 1) {
    url = startURL + `/?page=${currentPage}`;
  }

  const totalyResult = await nightmare
    .goto(url)
    .wait(getRandomNumber(1000, 3000))
    .evaluate(
      function(countNeeded, list) {
        const elements = document.querySelectorAll("#offers_table tr.wrap");

        elements.forEach(item => {
          const url = item.querySelector(".title-cell h3 a.link").href;
          const title = item.querySelector(".title-cell h3 strong").textContent;

          list = list.concat({ title, url });
        });

        return list;
      },
      countNeeded,
      list
    )
    .end()
    .then(function(result) {
      console.log(result.length);
      return result;
    });

  if (totalyResult.length >= countNeeded) {
    return totalyResult;
  } else {
    return await getCategoryList(
      url,
      pagesCount,
      currentPage + 1,
      totalyResult
    );
  }
};

const start = async () => {
  const nightmare = Nightmare();

  nightmare.useragent(
    "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36 OPR/38.0.2220.41"
  );

  const pagesCount = await nightmare
    .goto(startURL)
    .wait(getRandomNumber(1, 2))
    .evaluate(function() {
      const pagination = document.querySelectorAll(
        ".pager.rel.clr .item.fleft"
      );

      const result = pagination[pagination.length - 1].textContent;
      return result;
    })
    .end()
    .then(function(result) {
      return result;
    });

  const addsList = await getCategoryList(startURL, pagesCount);

  for (let [index, add] of addsList.entries()) {
    let __add = null;

    try {
      __add = await AddSchema.findOne({ url: add.url });
    } catch (err) {
      console.log(err);
    }

    if (__add) {
      console.log(__add, index);
      console.log("add allready exist");
      continue;
    }

    console.log("rrrr +=== =r==r==r= r= =r= =r= =r=r== + +==r==g ");

    requestWithCurrentProxy += 1;

    if (requestWithCurrentProxy == 5) {
      if (currentProxyUsed === proxies.length - 1) {
        currentProxyUsed = 0;
      } else {
        currentProxyUsed += 1;
      }
      requestWithCurrentProxy = 0;
    }

    console.log(index, add.url);

    let resukt = await scrapePhone(add.url);

    if(resukt === 'ERROR'){
      continue
    }

    console.log(resukt.user);
    if (!resukt.user.name || !resukt.user.mainPhone && !resukt.user.phone2 && !resukt.user.phone3) {
      console.log("somting wrong with numbres");
      console.log(resukt.user);
      continue;
    }

    await saveAd(resukt);
    console.log("SUCCESS");
    try {
      __add = await AddSchema.create({ url: add.url });
    } catch (err) {
      console.log(err);
    }
  }

  console.log("DONE DONE DONE");
};

start();

async function scrapePhone(url) {
  const nightmare = Nightmare({
    switches: {
      "proxy-server": `${proxies[currentProxyUsed].ip}:${proxyUser.port}` // set the proxy server here ...
    }
  });

  nightmare.useragent(proxies[currentProxyUsed].useragent);
  nightmare.authentication(proxyUser.login, proxyUser.password);

  const result = await nightmare
    .goto(url)
    .wait(getRandomNumber(1000, 3000))
    .evaluate(function() {
      var button = document.querySelector(
        "#contact_methods .contact-button .spoiler"
      );

      if (!button) {
        console.log("ERROR. BUTTON IS NOT EXIST");
        return;
      }

      button.click();
    })
    .wait(getRandomNumber(12000, 20000))
    .evaluate(
      function() {
        let title = document.querySelector("h1"),
          price = document.querySelector("#offerbox .price-label strong"),
          currency = "",
          description = document.querySelector(
            "#offerdescription #textContent"
          ),
          name = document.querySelector(
            "#offerbox .offer-sidebar__box .offer-user__details h4 a"
          ),
          nameUrl = document.querySelector(
            "#offerbox .offer-sidebar__box .offer-user__details h4 a"
          ),
          nameImage = document.querySelector(
            "#offerbox .offer-sidebar__box .offer-user__details img"
          ),
          _map = document.querySelector("#mapcontainer"),
          lat = "",
          lon = "",
          attributes = [],
          images = [],
          tempCategory = document.querySelector(
            "#breadcrumbTop .inline:last-child a span"
          ),
          objectAttributeValues = [],
          bargain = false;

        /**
         * olx profile url
         */

        if (title) {
          title = title.textContent.trim();
        }

        if (_map) {
          lat = _map.getAttribute("data-lat");
          lon = _map.getAttribute("data-lon");
        }
        if (name) {
          name = name.textContent.trim();
        }

        if (tempCategory) {
          tempCategory = tempCategory.textContent;
        }

        if (nameUrl) {
          nameUrl = nameUrl.href;
        } else {
          nameUrl = "";
        }

        if (nameImage) {
          nameImage = nameImage.src;
        } else {
          nameImage = "";
        }

        if (nameUrl && nameUrl.includes("list/user")) {
          nameUrl = nameUrl.split("list/user/")[1].replace(/\//, "");
        }

        if (description) {
          description = description.textContent.trim().replace(/\n/g, " ");

          /**
           * description remove тел. 957 - Показать номер -
           */
          while (description.includes("- Показать номер -")) {
            description = description.replace(
              /- Показать номер -/,
              "- номер в профиле -"
            );
          }
        }

        if (price) {
          price = price.textContent.trim();
          if (price.includes("грн.")) {
            currency = "UAH";
            price = price.split("грн.")[0].trim();
          } else if (price.includes("$")) {
            currency = "USD";
            price = price.split("$")[0].trim();
          } else if (price.includes("€")) {
            currency = "EUR";
            price = price.split("€")[0].trim();
          }
        }
        /**
         * price transform
         */

        const attributesElement =
          document.querySelectorAll(
            "#offerdescription .descriptioncontent .details .item"
          ) || [];

        if (attributesElement) {
          attributesElement.forEach(item => {
            attributes.push({
              attr: item.querySelector("th").textContent.trim(),
              value: item.querySelector("td strong").textContent.trim()
            });
          });
        }

        const imagesElement =
          document.querySelectorAll("#offerdescription .img-item img") || [];

        if (imagesElement) {
          imagesElement.forEach(item => {
            images.push({
              link: item.src,
              alt: item.alt
            });
          });
        }

        /* get images */

        let objectActionId = "",
          objectCategoryId = "",
          objectTypeId = "";

        /**
         * objectActionId
         */

         console.log(tempCategory)

        if (tempCategory.match(/Долгосрочная аренда/)) {
          objectActionId = 3;
        }
        if (tempCategory.match(/Аренда коммерческой недвижимости/)) {
          objectActionId = 3;
        }
        if (tempCategory.match(/Продажа/)) {
          objectActionId = 1;
        }
        if (tempCategory.match(/посуточно/)) {
          objectActionId = 2;
        }
        if (tempCategory.match(/койко/)) {
          objectActionId = 2;
        }

        if (tempCategory.match("посуточно")) {
          if (tempCategory.match("квартиры")) {
            objectTypeId = 1;
          }
          if (tempCategory.match("дома")) {
            objectTypeId = 3;
          }
          if (tempCategory.match("комнат")) {
            objectTypeId = 39;
            attributes.forEach(item => {
              switch (item.attr) {
                case "Планировка": {
                  switch (item.value) {
                    case "Пентхаус": {
                      objectTypeId = 8;
                      break;
                    }
                  }
                  break;
                }
              }
            });
          }
        }

        /**
         * objectTypeId
         */
        attributes.forEach(item => {
          switch (item.attr) {
            case "Тип объекта": {
              switch (item.value) {
                case "Квартира": {
                  objectTypeId = 1;
                  break;
                }
                case "Часть квартиры":
                case "Комната": {
                  objectTypeId = 39;
                  break;
                }
              }
            }
            case "Тип дома": {
              switch (item.value) {
                case "Дом":
                case "Коттедж":
                case "Таунхаус":
                case "Клубный дом": {
                  objectTypeId = 3;
                  break;
                }
                case "Часть дома": {
                  objectTypeId = 5;
                  break;
                }
                case "Дача": {
                  objectTypeId = 6;
                  break;
                }
              }
            }
            case "Тип недвижимости": {
              switch (item.value) {
                case "Земля сельскохозяйственного назначения": {
                  objectTypeId = 38;
                  break;
                }
                case "Земля жилой и общественной застройки": {
                  objectTypeId = 13;
                  break;
                }
                case "Земля запаса, резервного фонда и общего пользования":
                case "Земля промышленности, транспорта и другого назначения":
                case "Земля лесного фонда":
                case "Земля рекреационного назначения":
                case "Земля оздоровительного назначения": {
                  objectTypeId = 34;
                  break;
                }
                case "Магазин, салон": {
                  objectTypeId = 22;
                  break;
                }
                case "Кофейня":
                case "Ресторан, кафе, бар": {
                  objectTypeId = 23;
                  break;
                }
                case "Офисные помещения": {
                  objectTypeId = 29;
                  break;
                }
                case "Склад, ангар": {
                  objectTypeId = 31;
                  break;
                }
                case "Отдельно стоящие здания": {
                  objectTypeId = 33;
                  break;
                }
                case "Помещения свободного назначения":
                case "Часть здания": {
                  objectTypeId = 32;
                  break;
                }
                case "База отдыха, отель": {
                  objectTypeId = 26;
                  break;
                }
                case "Помещения промышленного назначения": {
                  objectTypeId = 28;
                  break;
                }
                case "Торговая точка на рынке": {
                  objectTypeId = 25;
                  break;
                }
                case "Фермерское хозяйство": {
                  objectTypeId = 24;
                  break;
                }
                case "Другое": {
                  objectTypeId = 37;
                  break;
                }
                case "Гараж":
                case "Место на парковке":
                case "Место на паркинге": {
                  objectTypeId = 30;
                  break;
                }
                case "Автомойка":
                case "Шиномонтаж":
                case "СТО (станция тех. обслуживания)":
                case "АЗС": {
                  objectTypeId = 19;
                  break;
                }
              }
            }
          }
        });

        /**
         * get attributes and values
         */

        /**
         * objectAttributeValues
         */
        attributes.forEach(item => {
          switch (item.attr) {
            case "Этаж": {
              objectAttributeValues.push({
                value: item.value,
                attributeValue: {
                  attributeId: 40
                }
              });
              break;
            }
            case "Этажность": {
              objectAttributeValues.push({
                value: item.value,
                attributeValue: {
                  attributeId: 12
                }
              });
              break;
            }
            case "Общая площадь": {
              if (objectTypeId == 1) {
                objectAttributeValues.push({
                  value: +item.value.split(" ")[0],
                  attributeValue: {
                    attributeId: 7
                  }
                });
              } else {
                objectAttributeValues.push({
                  value: item.value.split(" ")[0],
                  attributeValue: {
                    attributeId: 23
                  }
                });
              }
              break;
            }
            case "Площадь кухни": {
              objectAttributeValues.push({
                value: item.value.split(" ")[0],
                attributeValue: {
                  attributeId: 35
                }
              });
              break;
            }
            case "Количество комнат": {
              objectAttributeValues.push({
                value: item.value,
                attributeValue: {
                  attributeId: 15
                }
              });
              break;
            }
            case "Площадь участка": {
              objectAttributeValues.push({
                value: item.value.split(" ")[0],
                attributeValue: {
                  attributeId: 28
                }
              });
              break;
            }
            case "Без комиссии": {
              bargain = true;
              break;
            }
            case "Тип стен": {
              let tempVal, tempId;
              switch (item.value) {
                case "Кирпичный": {
                  tempVal = 27;
                  break;
                }
                case "Панельный": {
                  tempVal = 24;
                  break;
                }
                case "Монолитный": {
                  tempVal = 25;
                  break;
                }
                case "Деревянный": {
                  tempVal = 74;
                  break;
                }
                case "Шлакоблочный": {
                  tempVal = 28;
                  break;
                }
                case "Газоблок": {
                  tempVal = 560;
                  break;
                }
              }
              objectAttributeValues.push({
                value: tempVal,
                attributeValue: {
                  attributeId: 32,
                  type: "SELECT"
                }
              });
              break;
            }
            case "Планировка": {
              let tempVal;
              switch (item.value) {
                case "Смежная, проходная":
                case "Малосемейка, гостинка": {
                  tempVal = 14;
                  break;
                }
                case "Двухсторонняя":
                case "Смарт-квартира":
                case "Многоуровневая":
                case "Пентхаус":
                case "Раздельная": {
                  tempVal = 13;
                  break;
                }
                case "Свободная планировка":
                case "Студия": {
                  tempVal = 257;
                  break;
                }
              }
              objectAttributeValues.push({
                value: tempVal,
                attributeValue: {
                  attributeId: 8,
                  type: "SELECT"
                }
              });
              break;
            }
            case "Отопление": {
              let tempId, tempVal;

              switch (objectTypeId) {
                case 1:
                case 19: {
                  tempId = 29;
                  tempVal = 43;
                  break;
                }
                case 3:
                case 5:
                case 6: {
                  tempId = 74;
                  switch (item.value) {
                    case "Индивидуальное газовое": {
                      tempVal = 76;
                      break;
                    }
                    case "Индивидуальное электро": {
                      tempVal = 175;
                      break;
                    }
                    case "Твёрдотопливное": {
                      tempVal = 176;
                      break;
                    }
                    case "Тепловой насос": {
                      tempVal = 177;
                      break;
                    }
                    case "Комбинированное": {
                      tempVal = 481;
                      break;
                    }
                  }

                  break;
                }
                case 28:
                case 31:
                case 32:
                case 33: {
                  tempId = 73;
                  tempVal = 137;
                  break;
                }
                case 20:
                case 22:
                case 23:
                case 26:
                case 29:
                case 39: {
                  tempId = 29;
                  tempVal = 137;
                  break;
                }
                case 37: {
                  tempId = 74;
                  switch (item.value) {
                    case "Индивидуальное газовое": {
                      tempVal = 76;
                      break;
                    }
                    case "Индивидуальное электро": {
                      tempVal = 175;
                      break;
                    }
                    case "Твёрдотопливное": {
                      tempVal = 176;
                      break;
                    }
                    case "Тепловой насос": {
                      tempVal = 177;
                      break;
                    }
                    case "Комбинированное": {
                      tempVal = 481;
                      break;
                    }
                  }
                  break;
                }
              }
              switch (item.value) {
                case "Централизованное":
                case "Собственная котельная": {
                  tempVal = 42;
                }
              }
              objectAttributeValues.push({
                value: tempVal,
                attributeValue: {
                  attributeId: tempId,
                  type: "SELECT"
                }
              });
              break;
            }
            case "Ремонт": {
              let tempVal, tempId;
              switch (objectTypeId) {
                case 1:
                case 39: {
                  tempId = 22;
                }
                case 3:
                case 5:
                case 6: {
                  tempId = 22;
                }
                case 30:
                case 25: {
                  tempId = 76;
                }
                case 31:
                case 32:
                case 33:
                case 28:
                case 29:
                case 20:
                case 22:
                case 23:
                case 26:
                case 37:
                case 40: {
                  tempId = 22;
                }
              }
              switch (item.value) {
                case "Авторский проект": {
                  tempVal = 6;
                  break;
                }
                case "Евроремонт": {
                  tempVal = 5;
                  break;
                }
                case "Косметический ремонт": {
                  tempVal = 4;
                  break;
                }
                case "Жилое состояние": {
                  tempVal = 158;
                  break;
                }
                case "После строителей": {
                  tempVal = 231;
                  break;
                }
                case "Под чистовую отделку": {
                  tempVal = 232;
                  break;
                }
                case "Аварийное состояние": {
                  tempVal = 231;
                  break;
                }
              }
              objectAttributeValues.push({
                value: tempVal,
                attributeValue: {
                  attributeId: tempId,
                  type: "SELECT"
                }
              });
              break;
            }
            case "Меблирование": {
              objectAttributeValues.push({
                value: true,
                attributeValue: {
                  attributeId: 46
                }
              });
              break;
            }
            case "Бытовая техника": {
              let tempDescription = "<br>" + item.attr + ": ",
                tempValues = [],
                valuesArray = item.value
                  .split("\t")
                  .join("")
                  .split("\n\n");
              valuesArray.forEach(arrayItem => {
                switch (arrayItem) {
                  case "Плита":
                  case "Кулер":
                  case "Пылесос":
                  case "Без бытовой техники":
                  case "Сушильная машина":
                  case "Кофемашина":
                  case "Электрочайник":
                  case "Вентилятор, обогреватель":
                  case "Вентилятор":
                  case "обогреватель":
                  case "Посудомоечная машина":
                  case "Микроволновая печь":
                  case "Духовой шкаф":
                  case "Варочная панель": {
                    tempValues.push(arrayItem);
                    break;
                  }
                  case "Стиральная машина": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 24
                      }
                    });
                    break;
                  }
                  case "Холодильник": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 47
                      }
                    });
                    break;
                  }
                }
              });

              if (tempValues.length) {
                tempDescription += tempValues.join(", ");
                description += tempDescription;
              }
              break;
            }
            case "Мультимедиа": {
              let tempDescription = "<br>" + item.attr + ": ",
                tempValues = [],
                valuesArray = item.value
                  .split("\t")
                  .join("")
                  .split("\n\n");
              valuesArray.forEach(arrayItem => {
                switch (arrayItem) {
                  case "Wi-Fi":
                  case "Телевизор":
                  case "Без мультимедиа": {
                    tempValues.push(arrayItem);
                    break;
                  }
                  case "Кабельное, цифровое ТВ": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 39
                      }
                    });
                    break;
                  }
                  case "Спутниковое ТВ": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 45
                      }
                    });
                    break;
                  }
                  case "Скоростной интернет": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 36
                      }
                    });
                    break;
                  }
                }
              });

              if (tempValues.length) {
                tempDescription += tempValues.join(", ");
                description += tempDescription;
              }
              break;
            }
            case "Комфорт": {
              let tempDescription = "<br>" + item.attr + ": ",
                tempValues = [],
                valuesArray = item.value
                  .split("\t")
                  .join("")
                  .split("\n\n");
              valuesArray.forEach(arrayItem => {
                switch (arrayItem) {
                  case "Подогрев полов":
                  case "Ванна":
                  case "Гардероб":
                  case "Хоз. помещение, кладовка":
                  case "Хоз. помещение":
                  case "кладовка":
                  case "Гараж":
                  case "Ветровая электро станция":
                  case "Цоколь, подвал":
                  case "Цоколь":
                  case "подвал":
                  case "Сад, огород":
                  case "Сад":
                  case "огород":
                  case "Баcсейн":
                  case "Сауна, баня":
                  case "Сауна":
                  case "баня":
                  case "Спортзал":
                  case "Подсобные помещения":
                  case "Автонавес":
                  case "Беседка, мангал":
                  case "Беседка":
                  case "мангал":
                  case "Гостевой":
                  case "Гостевой, летний дом":
                  case "летний дом":
                  case "Автоматические ворота":
                  case "Забор, ограждение":
                  case "ограждение":
                  case "Забор":
                  case "Автономный электрогенератор":
                  case "Подземный паркинг":
                  case "Гостевой паркинг":
                  case "Парковочное место":
                  case "Решетки на окнах":
                  case "Панорамные окна":
                  case "Душевая кабина":
                  case "Мебель на кухне": {
                    tempValues.push(arrayItem);
                    break;
                  }
                  case "Балкон": {
                    objectAttributeValues.push({
                      value: 1,
                      attributeValue: {
                        attributeId: 25
                      }
                    });
                    break;
                  }
                  case "лоджия": {
                    objectAttributeValues.push({
                      value: 1,
                      attributeValue: {
                        attributeId: 25
                      }
                    });
                    break;
                  }
                  case "Кондиционер": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 48
                      }
                    });
                    break;
                  }
                  case "Терраса": {
                    objectAttributeValues.push({
                      value: 1,
                      attributeValue: {
                        attributeId: 25
                      }
                    });
                    break;
                  }
                  case "Сигнализация":
                  case "Консъерж":
                  case "Охраняемая территория":
                  case "Видеонаблюдение":
                  case "Пожарная сигнализация":
                  case "Сигнализация": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 42
                      }
                    });
                    break;
                  }
                  case "Лифт":
                  case "Грузовой лифт": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 33
                      }
                    });
                    break;
                  }
                  case 'Технология "умный дом"': {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 18
                      }
                    });
                    break;
                  }
                  case "Солнечные электро панели": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 54
                      }
                    });
                    break;
                  }
                }
              });

              if (tempValues.length) {
                tempDescription += tempValues.join(", ");
                description += tempDescription;
              }
              break;
            }
            case "Коммуникации": {
              let tempDescription = "<br>" + item.attr + ": ",
                tempValues = [],
                valuesArray = item.value
                  .split("\t")
                  .join("")
                  .split("\n\n");
              valuesArray.forEach(arrayItem => {
                switch (arrayItem) {
                  case "Вывоз отходов":
                  case "Асфальтированная дорога":
                  case "Без коммуникаций": {
                    tempValues.push(arrayItem);
                    break;
                  }
                  case "Газ": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 44
                      }
                    });
                    break;
                  }
                  case "Центральный водопровод": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 49
                      }
                    });
                    break;
                  }
                  case "Скважина": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 49
                      }
                    });
                    break;
                  }
                  case "Электричество": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 37
                      }
                    });
                    break;
                  }
                  case "Центральная канализация": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 41
                      }
                    });
                    break;
                  }
                  case "Канализация септик": {
                    objectAttributeValues.push({
                      value: true,
                      attributeValue: {
                        attributeId: 41
                      }
                    });
                    break;
                  }
                }
              });

              if (tempValues.length) {
                tempDescription += tempValues.join(", ");
                description += tempDescription;
              }
              break;
            }
            case "Инфраструктура":
            case "Инфраструктура (до 500 метров)": {
              let tempDescription = "<br>" + item.attr + ": ",
                tempValues = [],
                valuesArray = item.value
                  .split("\t")
                  .join("")
                  .split("\n\n");
              valuesArray.forEach(arrayItem => {
                tempValues.push(arrayItem);
              });

              if (tempValues.length) {
                tempDescription += tempValues.join(", ");
                description += tempDescription;
              }
              break;
            }
            case "Ландшафт": {
              let tempDescription = "<br>" + item.attr + ": ",
                tempValues = [],
                valuesArray = item.value
                  .split("\t")
                  .join("")
                  .split("\n\n");
              valuesArray.forEach(arrayItem => {
                tempValues.push(arrayItem);
              });

              if (tempValues.length) {
                tempDescription += tempValues.join(", ");
                description += tempDescription;
              }
              break;
            }
            case "Тип гаража": {
              let tempVal;
              switch (item.value) {
                case "Металлический": {
                  tempVal = 81;
                  break;
                }
                case "Кирпичный": {
                  tempVal = 27;
                  break;
                }
                case "Пеноблок": {
                  tempVal = 560;
                  break;
                }
                case "Бетонный": {
                  tempVal = 24;
                  break;
                }
              }

              objectAttributeValues.push({
                value: tempVal,
                attributeValue: {
                  attributeId: 77
                }
              });
              break;
            }
            case "Кадастровый номер": {
              description += "<br>" + item.attr + ": " + item.value;
              break;
            }
            case "Год постройки / сдачи": {
              objectAttributeValues.push({
                value: item.value,
                attributeValue: {
                  attributeId: 27
                }
              });
              break;
            }
            case "Внешнее утепление стен": {
              objectAttributeValues.push({
                value: true,
                attributeValue: {
                  attributeId: 14
                }
              });
              break;
            }
            case "Тип кровли": {
              description += "<br>" + item.attr + ": " + item.value;
              break;
            }
            case "Расположение": {
              description += "<br>" + item.attr + ": " + item.value;
              break;
            }
          }
        });

        /**
         * taken from active db table
         */
        let categoryToType = {
          1: [1, 2, 3, 4, 5, 6, 39],
          8: [27, 28],
          4: [29, 30, 31, 32, 33, 40, 20, 21, 22, 23, 26, 24, 19, 25, 37],
          5: [13, 34, 38]
          // 2: [1,2],
        };

        Object.keys(categoryToType).forEach(cat => {
          categoryToType[cat].forEach(typ => {
            if (typ == objectTypeId) {
              objectCategoryId = +cat;
            }
          });
        });

        var __phones = document.querySelector("#contact_methods .xx-large");

        let adObjectToLik = {
          description: description,
          price: {
            price: price.split(" ").join(""),
            currency: currency
          },
          coordinates: {
            lat: lat,
            lng: lon
          },
          title: title,
          bargain: bargain,
          exclusive: false,
          objectType: {
            typeId: objectTypeId
          },
          objectAction: {
            actionId: objectActionId
          },
          category: {
            cid: objectCategoryId
          },
          images: images,
          exact: true,
          objectAttributeValues: objectAttributeValues,
          user: {
            name: name,
            image: nameImage
          }
        };

        if (__phones) {
          if (__phones.childNodes[0]) {
            let phone = __phones.childNodes[0].textContent.replace(
              /[xXхХ]/gm,
              ""
            );

            if (phone.length > 5) {
              adObjectToLik.user.mainPhone = __phones.childNodes[0].textContent;
            } else {
              return "NEED RETRY";
            }
          }
          if (__phones.childNodes[2]) {
            let phone = __phones.childNodes[2].textContent;

            phone.replace(/[xXхХ]/gm, "");
            if (phone.length > 5) {
              adObjectToLik.user.phone2 = __phones.childNodes[2].textContent;
            } else {
              return "NEED RETRY";
            }
          }
          if (__phones.childNodes[4]) {
            let phone = __phones.childNodes[4].textContent;

            phone.replace(/[xXхХ]/gm, "");
            if (phone.length > 5) {
              adObjectToLik.user.phone3 = __phones.childNodes[4].textContent;
            } else {
              return "NEED RETRY";
            }
          }
        }

        return adObjectToLik;
      },
      lastUsedUserAgent,
      proxies,
      userAgents,
      currentProxyUsed,
      scrapePhone,
      url
    )
    .end()
    .then(async function(result) {
      if (result === "NEED RETRY") {
        requestWithCurrentProxy += 1;

        if (requestWithCurrentProxy == 5) {
          if (currentProxyUsed === proxies.length - 1) {
            currentProxyUsed = 0;
          } else {
            currentProxyUsed += 1;
          }
          requestWithCurrentProxy = 0;
        }

        proxies[currentProxyUsed].useragent = userAgents[lastUsedUserAgent];
        lastUsedUserAgent += 1;
        if (lastUsedUserAgent - 1 === userAgents.length) {
          lastUsedUserAgent = 0;
        }
        console.log(proxies[currentProxyUsed].useragent);
        console.log("RETRY WITH ANOTHER USER-AGENT");
        return await scrapePhone(url);
      }
      return result;
    })
    .catch(err => {
      console.log(err);

      return "ERROR";
    });

  console.log(result);

  return result;
}

function saveAd(data) {
  console.log("SAVE ADD START");

  return new Promise((resolve, reject) => {
    let result,
      url = "https://likimap.ua/parsed-add-object",
      // parsedUrl = urlModule.parse(url);
      parsedUrl = {};
    parsedUrl.headers = {
      // 'Cookie': cookies,
      "Content-Type": "application/json"
    };
    parsedUrl.method = "POST";
    parsedUrl.uri = url;

    parsedUrl.body = data;

    parsedUrl.json = true;

    rp(parsedUrl, (error, response, body) => {
      if (!error && body) {
        resolve(body);
      } else {
        console.log("ERROR", error);
        reject(error);
      }
    });
  });
}