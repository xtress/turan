/**
 * angular-translate - v1.1.1 - 2013-11-24
 * http://github.com/PascalPrecht/angular-translate
 * Copyright (c) 2013 ; Licensed 
 */
angular.module('pascalprecht.translate', ['ng']).run([
  '$translate',
  function ($translate) {
    var key = $translate.storageKey(), storage = $translate.storage();
    if (storage) {
      if (!storage.get(key)) {
        if (angular.isString($translate.preferredLanguage())) {
          $translate.uses($translate.preferredLanguage());
        } else {
          storage.set(key, $translate.uses());
        }
      } else {
        $translate.uses(storage.get(key));
      }
    } else if (angular.isString($translate.preferredLanguage())) {
      $translate.uses($translate.preferredLanguage());
    }
  }
]);
angular.module('pascalprecht.translate').provider('$translate', [
  '$STORAGE_KEY',
  function ($STORAGE_KEY) {
    var $translationTable = {}, $preferredLanguage, $fallbackLanguage, $uses, $nextLang, $storageFactory, $storageKey = $STORAGE_KEY, $storagePrefix, $missingTranslationHandlerFactory, $interpolationFactory, $interpolatorFactories = [], $loaderFactory, $loaderOptions, $notFoundIndicatorLeft, $notFoundIndicatorRight, NESTED_OBJECT_DELIMITER = '.';
    var translations = function (langKey, translationTable) {
      if (!langKey && !translationTable) {
        return $translationTable;
      }
      if (langKey && !translationTable) {
        if (angular.isString(langKey)) {
          return $translationTable[langKey];
        } else {
          angular.extend($translationTable, flatObject(langKey));
        }
      } else {
        if (!angular.isObject($translationTable[langKey])) {
          $translationTable[langKey] = {};
        }
        angular.extend($translationTable[langKey], flatObject(translationTable));
      }
      return this;
    };
    var flatObject = function (data, path, result, prevKey) {
      var key, keyWithPath, val;
      if (!path) {
        path = [];
      }
      if (!result) {
        result = {};
      }
      for (key in data) {
        if (!data.hasOwnProperty(key))
          continue;
        val = data[key];
        if (angular.isObject(val)) {
          flatObject(val, path.concat(key), result, key);
        } else {
          keyWithPath = path.length ? '' + path.join(NESTED_OBJECT_DELIMITER) + NESTED_OBJECT_DELIMITER + key : key;
          if (path.length && key === prevKey) {
            keyWithShortPath = '' + path.join(NESTED_OBJECT_DELIMITER);
            result[keyWithShortPath] = '@:' + keyWithPath;
          }
          result[keyWithPath] = val;
        }
      }
      return result;
    };
    this.translations = translations;
    this.addInterpolation = function (factory) {
      $interpolatorFactories.push(factory);
      return this;
    };
    this.useMessageFormatInterpolation = function () {
      return this.useInterpolation('$translateMessageFormatInterpolation');
    };
    this.useInterpolation = function (factory) {
      $interpolationFactory = factory;
      return this;
    };
    this.preferredLanguage = function (langKey) {
      if (langKey) {
        $preferredLanguage = langKey;
        return this;
      } else {
        return $preferredLanguage;
      }
    };
    this.translationNotFoundIndicator = function (indicator) {
      this.translationNotFoundIndicatorLeft(indicator);
      this.translationNotFoundIndicatorRight(indicator);
      return this;
    };
    this.translationNotFoundIndicatorLeft = function (indicator) {
      if (!indicator) {
        return $notFoundIndicatorLeft;
      }
      $notFoundIndicatorLeft = indicator;
      return this;
    };
    this.translationNotFoundIndicatorRight = function (indicator) {
      if (!indicator) {
        return $notFoundIndicatorRight;
      }
      $notFoundIndicatorRight = indicator;
      return this;
    };
    this.fallbackLanguage = function (langKey) {
      if (langKey) {
        if (typeof langKey === 'string' || angular.isArray(langKey)) {
          $fallbackLanguage = langKey;
        } else {
        }
        return this;
      } else {
        return $fallbackLanguage;
      }
    };
    this.uses = function (langKey) {
      if (langKey) {
        if (!$translationTable[langKey] && !$loaderFactory) {
          throw new Error('$translateProvider couldn\'t find translationTable for langKey: \'' + langKey + '\'');
        }
        $uses = langKey;
        return this;
      } else {
        return $uses;
      }
    };
    var storageKey = function (key) {
      if (!key) {
        if ($storagePrefix) {
          return $storagePrefix + $storageKey;
        }
        return $storageKey;
      }
      $storageKey = key;
    };
    this.storageKey = storageKey;
    this.useUrlLoader = function (url) {
      return this.useLoader('$translateUrlLoader', { url: url });
    };
    this.useStaticFilesLoader = function (options) {
      return this.useLoader('$translateStaticFilesLoader', options);
    };
    this.useLoader = function (loaderFactory, options) {
      $loaderFactory = loaderFactory;
      $loaderOptions = options || {};
      return this;
    };
    this.useLocalStorage = function () {
      return this.useStorage('$translateLocalStorage');
    };
    this.useCookieStorage = function () {
      return this.useStorage('$translateCookieStorage');
    };
    this.useStorage = function (storageFactory) {
      $storageFactory = storageFactory;
      return this;
    };
    this.storagePrefix = function (prefix) {
      if (!prefix) {
        return prefix;
      }
      $storagePrefix = prefix;
      return this;
    };
    this.useMissingTranslationHandlerLog = function () {
      return this.useMissingTranslationHandler('$translateMissingTranslationHandlerLog');
    };
    this.useMissingTranslationHandler = function (factory) {
      $missingTranslationHandlerFactory = factory;
      return this;
    };
    this.$get = [
      '$log',
      '$injector',
      '$rootScope',
      '$q',
      function ($log, $injector, $rootScope, $q) {
        var Storage, defaultInterpolator = $injector.get($interpolationFactory || '$translateDefaultInterpolation'), pendingLoader = false, interpolatorHashMap = {};
        var loadAsync = function (key) {
          if (!key) {
            throw 'No language key specified for loading.';
          }
          var deferred = $q.defer();
          $rootScope.$broadcast('$translateLoadingStart');
          pendingLoader = true;
          $injector.get($loaderFactory)(angular.extend($loaderOptions, { key: key })).then(function (data) {
            $rootScope.$broadcast('$translateLoadingSuccess');
            var translationTable = {};
            if (angular.isArray(data)) {
              angular.forEach(data, function (table) {
                angular.extend(translationTable, table);
              });
            } else {
              angular.extend(translationTable, data);
            }
            pendingLoader = false;
            deferred.resolve({
              key: key,
              table: translationTable
            });
            $rootScope.$broadcast('$translateLoadingEnd');
          }, function (key) {
            $rootScope.$broadcast('$translateLoadingError');
            deferred.reject(key);
            $rootScope.$broadcast('$translateLoadingEnd');
          });
          return deferred.promise;
        };
        if ($storageFactory) {
          Storage = $injector.get($storageFactory);
          if (!Storage.get || !Storage.set) {
            throw new Error('Couldn\'t use storage \'' + $storageFactory + '\', missing get() or set() method!');
          }
        }
        if ($interpolatorFactories.length > 0) {
          angular.forEach($interpolatorFactories, function (interpolatorFactory) {
            var interpolator = $injector.get(interpolatorFactory);
            interpolator.setLocale($preferredLanguage || $uses);
            interpolatorHashMap[interpolator.getInterpolationIdentifier()] = interpolator;
          });
        }
        var checkValidFallback = function (usesLang) {
          if (usesLang && $fallbackLanguage) {
            if (angular.isArray($fallbackLanguage)) {
              var fallbackLanguagesSize = $fallbackLanguage.length;
              for (var current = 0; current < fallbackLanguagesSize; current++) {
                if ($uses === $translationTable[$fallbackLanguage[current]]) {
                  return false;
                }
              }
              return true;
            } else {
              return usesLang !== $fallbackLanguage;
            }
          } else {
            return false;
          }
          return false;
        };
        var $translate = function (translationId, interpolateParams, interpolationId) {
          var table = $uses ? $translationTable[$uses] : $translationTable, Interpolator = interpolationId ? interpolatorHashMap[interpolationId] : defaultInterpolator;
          if (table && table.hasOwnProperty(translationId)) {
            if (angular.isString(table[translationId]) && table[translationId].substr(0, 2) === '@:') {
              return $translate(table[translationId].substr(2), interpolateParams, interpolationId);
            }
            return Interpolator.interpolate(table[translationId], interpolateParams);
          }
          if ($missingTranslationHandlerFactory && !pendingLoader) {
            $injector.get($missingTranslationHandlerFactory)(translationId, $uses);
          }
          var normatedLanguages;
          if ($uses && $fallbackLanguage && checkValidFallback($uses)) {
            if (typeof $fallbackLanguage === 'string') {
              normatedLanguages = [];
              normatedLanguages.push($fallbackLanguage);
            } else {
              normatedLanguages = $fallbackLanguage;
            }
            var fallbackLanguagesSize = normatedLanguages.length;
            for (var current = 0; current < fallbackLanguagesSize; current++) {
              if ($uses !== $translationTable[normatedLanguages[current]]) {
                var translationFromList = $translationTable[normatedLanguages[current]][translationId];
                if (translationFromList) {
                  var returnValFromList;
                  Interpolator.setLocale(normatedLanguages[current]);
                  returnValFromList = Interpolator.interpolate(translationFromList, interpolateParams);
                  Interpolator.setLocale($uses);
                  return returnValFromList;
                }
              }
            }
          }
          if ($notFoundIndicatorLeft) {
            translationId = [
              $notFoundIndicatorLeft,
              translationId
            ].join(' ');
          }
          if ($notFoundIndicatorRight) {
            translationId = [
              translationId,
              $notFoundIndicatorRight
            ].join(' ');
          }
          return translationId;
        };
        $translate.preferredLanguage = function () {
          return $preferredLanguage;
        };
        $translate.fallbackLanguage = function () {
          return $fallbackLanguage;
        };
        $translate.proposedLanguage = function () {
          return $nextLang;
        };
        $translate.storage = function () {
          return Storage;
        };
        $translate.uses = function (key) {
          if (!key) {
            return $uses;
          }
          var deferred = $q.defer();
          $rootScope.$broadcast('$translateChangeStart');
          function useLanguage(key) {
            $uses = key;
            $rootScope.$broadcast('$translateChangeSuccess');
            if ($storageFactory) {
              Storage.set($translate.storageKey(), $uses);
            }
            defaultInterpolator.setLocale($uses);
            angular.forEach(interpolatorHashMap, function (interpolator, id) {
              interpolatorHashMap[id].setLocale($uses);
            });
            deferred.resolve(key);
            $rootScope.$broadcast('$translateChangeEnd');
          }
          if (!$translationTable[key] && $loaderFactory) {
            $nextLang = key;
            loadAsync(key).then(function (translation) {
              $nextLang = undefined;
              translations(translation.key, translation.table);
              useLanguage(translation.key);
            }, function (key) {
              $nextLang = undefined;
              $rootScope.$broadcast('$translateChangeError');
              deferred.reject(key);
              $rootScope.$broadcast('$translateChangeEnd');
            });
          } else {
            useLanguage(key);
          }
          return deferred.promise;
        };
        $translate.storageKey = function () {
          return storageKey();
        };
        $translate.refresh = function (langKey) {
          if (!$loaderFactory) {
            throw new Error('Couldn\'t refresh translation table, no loader registered!');
          }
          var deferred = $q.defer();
          function onLoadSuccess() {
            deferred.resolve();
            $rootScope.$broadcast('$translateRefreshEnd');
          }
          function onLoadFailure() {
            deferred.reject();
            $rootScope.$broadcast('$translateRefreshEnd');
          }
          if (!langKey) {
            $rootScope.$broadcast('$translateRefreshStart');
            var loaders = [];
            if ($fallbackLanguage) {
              if (typeof $fallbackLanguage === 'string') {
                loaders.push(loadAsync($fallbackLanguage));
              } else {
                var fallbackLanguagesSize = $fallbackLanguage.length;
                for (var current = 0; current < fallbackLanguagesSize; current++) {
                  loaders.push(loadAsync($fallbackLanguage[current]));
                }
              }
            }
            if ($uses) {
              loaders.push(loadAsync($uses));
            }
            if (loaders.length > 0) {
              $q.all(loaders).then(function (newTranslations) {
                for (var lang in $translationTable) {
                  if ($translationTable.hasOwnProperty(lang)) {
                    delete $translationTable[lang];
                  }
                }
                for (var i = 0, len = newTranslations.length; i < len; i++) {
                  translations(newTranslations[i].key, newTranslations[i].table);
                }
                if ($uses) {
                  $translate.uses($uses);
                }
                onLoadSuccess();
              }, function (key) {
                if (key === $uses) {
                  $rootScope.$broadcast('$translateChangeError');
                }
                onLoadFailure();
              });
            } else
              onLoadSuccess();
          } else if ($translationTable.hasOwnProperty(langKey)) {
            $rootScope.$broadcast('$translateRefreshStart');
            var loader = loadAsync(langKey);
            if (langKey === $uses) {
              loader.then(function (newTranslation) {
                $translationTable[langKey] = newTranslation.table;
                $translate.uses($uses);
                onLoadSuccess();
              }, function () {
                $rootScope.$broadcast('$translateChangeError');
                onLoadFailure();
              });
            } else {
              loader.then(function (newTranslation) {
                $translationTable[langKey] = newTranslation.table;
                onLoadSuccess();
              }, onLoadFailure);
            }
          } else
            deferred.reject();
          return deferred.promise;
        };
        if ($loaderFactory) {
          if (angular.equals($translationTable, {})) {
            $translate.uses($translate.uses());
          }
          if ($fallbackLanguage) {
            if (typeof $fallbackLanguage === 'string' && !$translationTable[$fallbackLanguage]) {
              loadAsync($fallbackLanguage);
            } else {
              var fallbackLanguagesSize = $fallbackLanguage.length;
              for (var current = 0; current < fallbackLanguagesSize; current++) {
                if (!$translationTable[$fallbackLanguage[current]]) {
                  loadAsync($fallbackLanguage[current]);
                }
              }
            }
          }
        }
        return $translate;
      }
    ];
  }
]);
angular.module('pascalprecht.translate').factory('$translateDefaultInterpolation', [
  '$interpolate',
  function ($interpolate) {
    var $translateInterpolator = {}, $locale, $identifier = 'default';
    $translateInterpolator.setLocale = function (locale) {
      $locale = locale;
    };
    $translateInterpolator.getInterpolationIdentifier = function () {
      return $identifier;
    };
    $translateInterpolator.interpolate = function (string, interpolateParams) {
      return $interpolate(string)(interpolateParams);
    };
    return $translateInterpolator;
  }
]);
angular.module('pascalprecht.translate').constant('$STORAGE_KEY', 'NG_TRANSLATE_LANG_KEY');
angular.module('pascalprecht.translate').directive('translate', [
  '$filter',
  '$interpolate',
  '$parse',
  function ($filter, $interpolate, $parse) {
    var translate = $filter('translate');
    return {
      restrict: 'AE',
      scope: true,
      link: function linkFn(scope, element, attr) {
        if (attr.translateInterpolation) {
          scope.interpolation = attr.translateInterpolation;
        }
        attr.$observe('translate', function (translationId) {
          if (angular.equals(translationId, '') || translationId === undefined) {
            scope.translationId = $interpolate(element.text().replace(/^\s+|\s+$/g, ''))(scope.$parent);
          } else {
            scope.translationId = translationId;
          }
        });
        attr.$observe('translateValues', function (interpolateParams) {
          if (interpolateParams)
            scope.$parent.$watch(function () {
              scope.interpolateParams = $parse(interpolateParams)(scope.$parent);
            });
        });
        scope.$on('$translateChangeSuccess', function () {
          element.html(translate(scope.translationId, scope.interpolateParams, scope.interpolation));
        });
        scope.$watch('[translationId, interpolateParams]', function (nValue) {
          if (scope.translationId) {
            element.html(translate(scope.translationId, scope.interpolateParams, scope.interpolation));
          }
        }, true);
      }
    };
  }
]);
angular.module('pascalprecht.translate').filter('translate', [
  '$parse',
  '$translate',
  function ($parse, $translate) {
    return function (translationId, interpolateParams, interpolation) {
      if (!angular.isObject(interpolateParams)) {
        interpolateParams = $parse(interpolateParams)();
      }
      return $translate(translationId, interpolateParams, interpolation);
    };
  }
]);
angular.module('pascalprecht.translate')
/**
 * @ngdoc object
 * @name pascalprecht.translate.$translatePartialLoaderProvider
 *
 * @description
 * By using a $translatePartialLoaderProvider you can configure a list of a needed translation parts
 * directly during the configuration phase of your application's lifetime. All parts you add by
 * using this provider would be loaded by the angular-translate at the startup as soon as possible.
 */
    .provider('$translatePartialLoader', [function() {

        function Part(name) {
            this.name = name;
            this.isActive = true;
            this.tables = {};
        }

        Part.prototype.parseUrl = function(urlTemplate, targetLang) {
            return urlTemplate.replace(/\{part\}/g, this.name).replace(/\{lang\}/g, targetLang);
        };

        Part.prototype.getTable = function(lang, $q, $http, urlTemplate, errorHandler) {
            var deferred = $q.defer();

            if (!this.tables.hasOwnProperty(lang)) {
                var self = this;

                $http({
                    method : 'GET',
                    url : this.parseUrl(urlTemplate, lang)
                }).success(function(data){
                        self.tables[lang] = data;
                        deferred.resolve(data);
                    }).error(function() {
                        if (errorHandler !== undefined) {
                            errorHandler(self.name, lang).then(
                                function(data) {
                                    self.tables[lang] = data;
                                    deferred.resolve(data);
                                },
                                function() {
                                    deferred.reject(self.name);
                                }
                            );
                        } else deferred.reject(self.name);
                    });

            } else deferred.resolve(this.tables[lang]);

            return deferred.promise;
        };


        var parts = {};

        function hasPart(name) {
            return parts.hasOwnProperty(name);
        }

        function isStringValid(str) {
            return angular.isString(str) && str !== '';
        }

        function isPartAvailable(name) {
            if (!isStringValid(name)) {
                throw new TypeError('Invalid type of a first argument, a non-empty string expected.');
            }

            return (hasPart(name) && parts[name].isActive);
        }

        function deepExtend(dst, src) {
            for (var property in src) {
                if (src[property] && src[property].constructor &&
                    src[property].constructor === Object) {
                    dst[property] = dst[property] || {};
                    arguments.callee(dst[property], src[property]);
                } else {
                    dst[property] = src[property];
                }
            }
            return dst;
        }


        /**
         * @ngdoc function
         * @name pascalprecht.translate.$translatePartialLoaderProvider#addPart
         * @methodOf pascalprecht.translate.$translatePartialLoaderProvider
         *
         * @description
         * Registers a new part of the translation table to be loaded once the `angular-translate` gets
         * into runtime phase. It does not actually load any translation data, but only registers a part
         * to be loaded in the future.
         *
         * @param {string} name A name of the part to add
         *
         * @returns {object} $translatePartialLoaderProvider, so this method is chainable
         *
         * @throws {TypeError} The method could throw a **TypeError** if you pass the param of the wrong
         * type. Please, note that the `name` param has to be a non-empty **string**.
         */
        this.addPart = function(name) {
            if (!isStringValid(name)) {
                throw new TypeError('Invalid type of a first argument, a non-empty string expected.');
            }

            if (!hasPart(name)) {
                parts[name] = new Part(name);
            }
            parts[name].isActive = true;

            return this;
        };

        /**
         * @ngdocs function
         * @name pascalprecht.translate.$translatePartialLoaderProvider#setPart
         * @methodOf pascalprecht.translate.$translatePartialLoaderProvider
         *
         * @description
         * Sets a translation table to the specified part. This method does not make the specified part
         * available, but only avoids loading this part from the server.
         *
         * @param {string} lang A language of the given translation table
         * @param {string} part A name of the target part
         * @param {object} table A translation table to set to the specified part
         *
         * @return {object} $translatePartialLoaderProvider, so this method is chainable
         *
         * @throws {TypeError} The method could throw a **TypeError** if you pass params of the wrong
         * type. Please, note that the `lang` and `part` params have to be a non-empty **string**s and
         * the `table` param has to be an object.
         */
        this.setPart = function(lang, part, table) {
            if (!isStringValid(lang)) {
                throw new TypeError('Invalid type of a first argument, a non-empty string expected.');
            }
            if (!isStringValid(part)) {
                throw new TypeError('Invalid type of a second argument, a non-empty string expected.');
            }
            if (typeof table !== 'object' || table === null) {
                throw new TypeError('Invalid type of a third argument, an object expected.');
            }

            if (!hasPart(part)) {
                parts[part] = new Part(part);
                parts[part].isActive = false;
            }

            parts[part].tables[lang] = table;
            return this;
        };

        /**
         * @ngdoc function
         * @name pascalprecht.translate.$translatePartialLoaderProvider#deletePart
         * @methodOf pascalprecht.translate.$translatePartialLoaderProvider
         *
         * @description
         * Removes the previously added part of the translation data. So, `angular-translate` will not
         * load it at the startup.
         *
         * @param {string} name A name of the part to delete
         *
         * @returns {object} $translatePartialLoaderProvider, so this method is chainable
         *
         * @throws {TypeError} The method could throw a **TypeError** if you pass the param of the wrong
         * type. Please, note that the `name` param has to be a non-empty **string**.
         */
        this.deletePart = function(name) {
            if (!isStringValid(name)) {
                throw new TypeError('Invalid type of a first argument, a non-empty string expected.');
            }

            if (hasPart(name)) {
                parts[name].isActive = false;
            }

            return this;
        };


        /**
         * @ngdoc function
         * @name pascalprecht.translate.$translatePartialLoaderProvider#isPartAvailable
         * @methodOf pascalprecht.translate.$translatePartialLoaderProvider
         *
         * @description
         * Checks if the specific part is available. A part becomes available after it was added by the
         * `addPart` method. Available parts would be loaded from the server once the `angular-translate`
         * asks the loader to that.
         *
         * @param {string} name A name of the part to check
         *
         * @returns {boolean} Returns **true** if the part is available now and **false** if not.
         *
         * @throws {TypeError} The method could throw a **TypeError** if you pass the param of the wrong
         * type. Please, note that the `name` param has to be a non-empty **string**.
         */
        this.isPartAvailable = isPartAvailable;

        /**
         * @ngdoc object
         * @name pascalprecht.translate.$translatePartialLoader
         *
         * @requires $q
         * @requires $http
         * @requires $injector
         * @requires $rootScope
         *
         * @description
         *
         * @param {object} options
         *
         * @throws {TypeError}
         */
        this.$get = ['$rootScope', '$injector', '$q', '$http',
            function($rootScope, $injector, $q, $http) {

                /**
                 * @ngdoc event
                 * @name pascalprecht.translate.$translatePartialLoader#$translatePartialLoaderStructureChanged
                 * @eventOf pascalprecht.translate.$translatePartialLoader
                 * @eventType broadcast on root scope
                 *
                 * @description
                 * A $translatePartialLoaderStructureChanged event is called when a state of the loader was
                 * changed somehow. It could mean either some part is added or some part is deleted. Anyway when
                 * you get this event the translation table is not longer current and has to be updated.
                 *
                 * @param {string} name A name of the part which is a reason why the event was fired
                 */

                var service = function(options) {
                    if (!isStringValid(options.key)) {
                        throw new TypeError('Unable to load data, a key is not a non-empty string.');
                    }

                    if (!isStringValid(options.urlTemplate)) {
                        throw new TypeError('Unable to load data, a urlTemplate is not a non-empty string.');
                    }

                    var errorHandler = options.loadFailureHandler;
                    if (errorHandler !== undefined) {
                        if (!angular.isString(errorHandler)) {
                            throw new Error('Unable to load data, a loadFailureHandler is not a string.');
                        } else errorHandler = $injector.get(errorHandler);
                    }

                    var loaders = [],
                        tables = [],
                        deferred = $q.defer();

                    function addTablePart(table) {
                        tables.push(table);
                    }

                    for (var part in parts) {
                        if (hasPart(part) && parts[part].isActive) {
                            loaders.push(
                                parts[part]
                                    .getTable(options.key, $q, $http, options.urlTemplate, errorHandler)
                                    .then(addTablePart)
                            );
                        }
                    }

                    if (loaders.length) {
                        $q.all(loaders).then(
                            function() {
                                var table = {};
                                for (var i = 0; i < tables.length; i++) {
                                    deepExtend(table, tables[i]);
                                }
                                deferred.resolve(table);
                            },
                            function() {
                                deferred.reject(options.key);
                            }
                        );
                    } else {
                        deferred.resolve({});
                    }

                    return deferred.promise;
                };

                /**
                 * @ngdoc function
                 * @name pascalprecht.translate.$translatePartialLoader#addPart
                 * @methodOf pascalprecht.translate.$translatePartialLoader
                 *
                 * @description
                 * Registers a new part of the translation table. This method does actually not perform any xhr
                 * requests to get a translation data. The new parts would be loaded from the server next time
                 * `angular-translate` asks to loader to loaded translations.
                 *
                 * @param {string} name A name of the part to add
                 *
                 * @returns {object} $translatePartialLoader, so this method is chainable
                 *
                 * @fires {$translatePartialLoaderStructureChanged} The $translatePartialLoaderStructureChanged
                 * event would be fired by this method in case the new part affected somehow on the loaders
                 * state. This way it means that there are a new translation data available to be loaded from
                 * the server.
                 *
                 * @throws {TypeError} The method could throw a **TypeError** if you pass the param of the wrong
                 * type. Please, note that the `name` param has to be a non-empty **string**.
                 */
                service.addPart = function(name) {
                    if (!isStringValid(name)) {
                        throw new TypeError('Invalid type of a first argument, a non-empty string expected.');
                    }

                    if (!hasPart(name)) {
                        parts[name] = new Part(name);
                        $rootScope.$broadcast('$translatePartialLoaderStructureChanged', name);
                    } else if (!parts[name].isActive) {
                        parts[name].isActive = true;
                        $rootScope.$broadcast('$translatePartialLoaderStructureChanged', name);
                    }

                    return service;
                };

                /**
                 * @ngdoc function
                 * @name pascalprecht.translate.$translatePartialLoader#deletePart
                 * @methodOf pascalprecht.translate.$translatePartialLoader
                 *
                 * @description
                 * Deletes the previously added part of the translation data. The target part could be deleted
                 * either logically or physically. When the data is deleted logically it is not actually deleted
                 * from the browser, but the loader marks it as not active and prevents it from affecting on the
                 * translations. If the deleted in such way part is added again, the loader will use the
                 * previously loaded data rather than loading it from the server once more time. But if the data
                 * is deleted physically, the loader will completely remove all information about it. So in case
                 * of recycling this part will be loaded from the server again.
                 *
                 * @param {string} name A name of the part to delete
                 * @param {boolean=} [removeData=false] An indicator if the loader has to remove a loaded
                 * translation data physically. If the `removeData` if set to **false** the loaded data will not be
                 * deleted physically and might be reused in the future to prevent an additional xhr requests.
                 *
                 * @returns {object} $translatePartialLoader, so this method is chainable
                 *
                 * @fires {$translatePartialLoaderStructureChanged} The $translatePartialLoaderStructureChanged
                 * event would be fired by this method in case a part deletion process affects somehow on the
                 * loaders state. This way it means that some part of the translation data is now deprecated and
                 * the translation table has to be recompiled with the remaining translation parts.
                 *
                 * @throws {TypeError} The method could throw a **TypeError** if you pass some param of the
                 * wrong type. Please, note that the `name` param has to be a non-empty **string** and
                 * the `removeData` param has to be either **undefined** or **boolean**.
                 */
                service.deletePart = function(name, removeData) {
                    if (!isStringValid(name)) {
                        throw new TypeError('Invalid type of a first argument, a non-empty string expected.');
                    }

                    if (removeData === undefined) {
                        removeData = false;
                    } else if (typeof removeData !== 'boolean') {
                        throw new TypeError('Invalid type of a second argument, a boolean expected.');
                    }

                    if (hasPart(name)) {
                        var wasActive = parts[name].isActive;
                        if (removeData) {
                            delete parts[name];
                        } else {
                            parts[name].isActive = false;
                        }
                        if (wasActive) {
                            $rootScope.$broadcast('$translatePartialLoaderStructureChanged', name);
                        }
                    }

                    return service;
                };

                /**
                 * @ngdoc function
                 * @name pascalprecht.translate.$translatePartialLoader#isPartAvailable
                 * @methodOf pascalprecht.translate.$translatePartialLoader
                 *
                 * @description
                 * Checks if a target translation part is available. The part becomes available just after it was
                 * added by the `addPart` method. Part's availability does not mean that it was loaded from the
                 * server, but only that it was added to the loader. The available part might be loaded next
                 * time the loader is called.
                 *
                 * @param {string} name A name of the part to delete
                 *
                 * @returns {boolean} Returns **true** if the part is available now and **false** if not.
                 *
                 * @throws {TypeError} The method could throw a **TypeError** if you pass the param of the wrong
                 * type. Please, note that the `name` param has to be a non-empty **string**.
                 */
                service.isPartAvailable = isPartAvailable;

                return service;

            }];

    }]);